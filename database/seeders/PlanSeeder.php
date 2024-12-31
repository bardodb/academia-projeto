<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Plano Básico
        Plan::create([
            'name' => 'Plano Básico',
            'description' => "Ideal para quem busca atividades físicas essenciais a um custo acessível.\n\nInclui:\n- Acesso à academia em horários reduzidos (segunda a sexta, das 9h às 17h).\n- Uso dos equipamentos de musculação e cardio.\n- Acompanhamento inicial com avaliação física e plano básico de treino.",
            'price' => 59.90,
            'duration_months' => 1,
            'classes_per_week' => 0,
            'active' => true,
        ]);

        // Plano Médio
        Plan::create([
            'name' => 'Plano Médio',
            'description' => "Perfeito para quem deseja mais flexibilidade e serviços adicionais.\n\nInclui:\n- Acesso livre à academia durante o horário de funcionamento.\n- Uso ilimitado dos equipamentos de musculação, cardio e área de alongamento.\n- Participação em aulas coletivas (ex.: yoga, pilates, funcional, spinning).\n- Avaliação física completa com reavaliação a cada 3 meses.\n- Plano de treino personalizado.",
            'price' => 99.90,
            'duration_months' => 1,
            'classes_per_week' => 7,
            'active' => true,
        ]);

        // Plano Custo Alto
        Plan::create([
            'name' => 'Plano Custo Alto',
            'description' => "Para quem quer aproveitar uma experiência premium com mais benefícios.\n\nInclui:\n- Todos os benefícios do plano médio.\n- Acesso à sauna e área de relaxamento.\n- Participação em grupos exclusivos de treinos avançados.\n- Avaliação física mensal com personal trainer.\n- Bebidas isotônicas ou snacks fitness inclusos semanalmente.",
            'price' => 159.90,
            'duration_months' => 2,
            'classes_per_week' => 7,
            'active' => true,
        ]);

        // Plano Business
        Plan::create([
            'name' => 'Plano Business',
            'description' => "Desenvolvido para profissionais que precisam de conveniência e serviços exclusivos.\n\nInclui:\n- Todos os benefícios do plano de custo alto.\n- Vestiário privativo com kit de toalhas incluso.\n- Personal trainer individual 1x por semana.\n- Agendamento prioritário de horários para treinos e aulas coletivas.\n- Espaço para coworking com Wi-Fi de alta velocidade.\n- Descontos em lojas de suplementos e roupas fitness parceiras.",
            'price' => 249.90,
            'duration_months' => 3,
            'classes_per_week' => 7,
            'active' => true,
        ]);
    }
}
