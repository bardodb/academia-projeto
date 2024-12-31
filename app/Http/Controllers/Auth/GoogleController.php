<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\Student;
use Exception;
use Google\Client as GoogleClient;
use Google\Service\Fitness;
use Carbon\Carbon;

class GoogleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function show()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your fitness data.');
        }

        return view('student.google-fit', compact('student'));
    }

    public function redirectToGoogle()
    {
        $scopes = [
            'https://www.googleapis.com/auth/fitness.activity.read',
            'https://www.googleapis.com/auth/fitness.body.read',
            'https://www.googleapis.com/auth/fitness.heart_rate.read',
            'https://www.googleapis.com/auth/fitness.nutrition.read'
        ];

        try {
            return Socialite::driver('google')
                ->scopes($scopes)
                ->with([
                    "access_type" => "offline",
                    "prompt" => "consent select_account"
                ])
                ->redirect();
        } catch (Exception $e) {
            report($e);
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to connect to Google. Please try again.');
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user || !$user->student) {
                return redirect()->route('login')
                    ->with('error', 'Please log in to connect Google Fit.');
            }

            $googleUser = Socialite::driver('google')->user();
            
            $user->student->update([
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
                'google_connected' => true,
                'last_sync_at' => now(),
            ]);

            // Initial sync
            $this->syncFitnessData($user->student);

            return redirect()->route('google.show')
                ->with('success', 'Successfully connected to Google Fit!');

        } catch (Exception $e) {
            report($e);
            return redirect()->route('student.dashboard')
                ->with('error', 'Failed to connect Google Fit: ' . $e->getMessage());
        }
    }

    public function sync()
    {
        try {
            $student = auth()->user()->student;
            
            if (!$student) {
                return redirect()->route('login')
                    ->with('error', 'Please log in to sync fitness data.');
            }

            if (!$student->google_connected) {
                return redirect()->route('google.show')
                    ->with('error', 'Please connect your Google Fit account first.');
            }

            $this->syncFitnessData($student);

            return redirect()->route('google.show')
                ->with('success', 'Successfully synced fitness data!');
        } catch (Exception $e) {
            report($e);
            return redirect()->route('google.show')
                ->with('error', 'Failed to sync fitness data: ' . $e->getMessage());
        }
    }

    protected function syncFitnessData(Student $student)
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setAccessToken($student->google_token);

        if ($client->isAccessTokenExpired() && $student->google_refresh_token) {
            $client->fetchAccessTokenWithRefreshToken($student->google_refresh_token);
            $student->update(['google_token' => $client->getAccessToken()]);
        }

        $fitness = new Fitness($client);
        
        // Get today's data
        $endTime = Carbon::now();
        $startTime = $endTime->copy()->startOfDay();

        $fitnessData = [
            'steps' => 0,
            'distance' => 0,
            'calories' => 0,
            'heart_rate' => [
                'average' => null,
                'min' => null,
                'max' => null,
            ],
            'weight' => null,
            'height' => null,
            'bmi' => null,
        ];

        try {
            // Fetch activity data
            $request = new \Google_Service_Fitness_AggregateRequest([
                'aggregateBy' => [
                    [
                        'dataTypeName' => 'com.google.step_count.delta',
                    ],
                    [
                        'dataTypeName' => 'com.google.distance.delta',
                    ],
                    [
                        'dataTypeName' => 'com.google.calories.expended',
                    ],
                    [
                        'dataTypeName' => 'com.google.heart_rate.bpm',
                    ],
                ],
                'startTimeMillis' => $startTime->timestamp * 1000,
                'endTimeMillis' => $endTime->timestamp * 1000,
            ]);

            $response = $fitness->users_dataset->aggregate('me', $request);

            foreach ($response->getBucket() as $bucket) {
                foreach ($bucket->getDataset() as $dataset) {
                    foreach ($dataset->getPoint() as $point) {
                        $value = $point->getValue()[0]->getIntVal() ?? $point->getValue()[0]->getFpVal();
                        
                        switch ($dataset->getDataSourceId()) {
                            case 'derived:com.google.step_count.delta':
                                $fitnessData['steps'] += $value;
                                break;
                            case 'derived:com.google.distance.delta':
                                $fitnessData['distance'] += $value;
                                break;
                            case 'derived:com.google.calories.expended':
                                $fitnessData['calories'] += $value;
                                break;
                            case 'derived:com.google.heart_rate.bpm':
                                if (!$fitnessData['heart_rate']['min'] || $value < $fitnessData['heart_rate']['min']) {
                                    $fitnessData['heart_rate']['min'] = $value;
                                }
                                if (!$fitnessData['heart_rate']['max'] || $value > $fitnessData['heart_rate']['max']) {
                                    $fitnessData['heart_rate']['max'] = $value;
                                }
                                if (!$fitnessData['heart_rate']['average']) {
                                    $fitnessData['heart_rate']['average'] = $value;
                                } else {
                                    $fitnessData['heart_rate']['average'] = ($fitnessData['heart_rate']['average'] + $value) / 2;
                                }
                                break;
                        }
                    }
                }
            }

            $student->update([
                'google_fit_data' => $fitnessData,
                'last_sync_at' => now(),
            ]);

            return true;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function disconnect()
    {
        try {
            $student = auth()->user()->student;
            
            if (!$student) {
                return redirect()->route('login')
                    ->with('error', 'Please log in to disconnect Google Fit.');
            }

            $student->update([
                'google_token' => null,
                'google_refresh_token' => null,
                'google_fit_data' => null,
                'last_sync_at' => null,
                'google_connected' => false,
            ]);

            return redirect()->route('google.show')
                ->with('success', 'Successfully disconnected from Google Fit.');
        } catch (Exception $e) {
            report($e);
            return redirect()->route('google.show')
                ->with('error', 'Failed to disconnect Google Fit: ' . $e->getMessage());
        }
    }
} 