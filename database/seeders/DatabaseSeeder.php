<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== 1. CRIAR ADMIN =====
        $admin = User::create([
            'name' => 'Derek Admin',
            'email' => 'admin@admin.com',
            'phone' => '(11) 98765-4321',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ===== 2. CRIAR RESPONSÁVEIS (PAIS) =====
        $responsaveis = [
            ['name' => 'Maria Silva', 'email' => 'maria.silva@email.com', 'phone' => '(11) 99999-0001'],
            ['name' => 'João Santos', 'email' => 'joao.santos@email.com', 'phone' => '(11) 99999-0002'],
            ['name' => 'Ana Costa', 'email' => 'ana.costa@email.com', 'phone' => '(11) 99999-0003'],
            ['name' => 'Paulo Oliveira', 'email' => 'paulo.oliveira@email.com', 'phone' => '(11) 99999-0004'],
            ['name' => 'Carla Pereira', 'email' => 'carla.pereira@email.com', 'phone' => '(11) 99999-0005'],
        ];

        $responsaveisCreated = [];
        foreach ($responsaveis as $resp) {
            $responsaveisCreated[] = User::create([
                'name' => $resp['name'],
                'email' => $resp['email'],
                'phone' => $resp['phone'],
                'password' => Hash::make('password'),
                'role' => 'responsavel',
            ]);
        }

        // ===== 3. CRIAR PROFESSORES =====
        $professores = [
            ['name' => 'Prof. Carlos Matemática', 'email' => 'carlos.mat@escola.com', 'phone' => '(11) 98888-0001'],
            ['name' => 'Profa. Juliana Português', 'email' => 'juliana.port@escola.com', 'phone' => '(11) 98888-0002'],
            ['name' => 'Prof. Roberto Ciências', 'email' => 'roberto.ciencias@escola.com', 'phone' => '(11) 98888-0003'],
            ['name' => 'Profa. Fernanda Inglês', 'email' => 'fernanda.ingles@escola.com', 'phone' => '(11) 98888-0004'],
        ];

        $professoresCreated = [];
        foreach ($professores as $prof) {
            $professoresCreated[] = User::create([
                'name' => $prof['name'],
                'email' => $prof['email'],
                'phone' => $prof['phone'],
                'password' => Hash::make('password'),
                'role' => 'professor',
            ]);
        }

        // ===== 4. CRIAR TURMAS =====
        $turmas = [
            // Prof. Carlos
            ['name' => 'Matemática - 6º Ano A', 'time' => '08:00 - 09:30', 'teacher' => $professoresCreated[0]],
            ['name' => 'Matemática - 6º Ano B', 'time' => '10:00 - 11:30', 'teacher' => $professoresCreated[0]],
            ['name' => 'Reforço Matemática', 'time' => '14:00 - 15:30', 'teacher' => $professoresCreated[0]],
            // Profa. Juliana
            ['name' => 'Português - 7º Ano A', 'time' => '08:00 - 09:30', 'teacher' => $professoresCreated[1]],
            ['name' => 'Português - 7º Ano B', 'time' => '10:00 - 11:30', 'teacher' => $professoresCreated[1]],
            // Prof. Roberto
            ['name' => 'Ciências - 8º Ano', 'time' => '13:00 - 14:30', 'teacher' => $professoresCreated[2]],
            // Profa. Fernanda
            ['name' => 'Inglês - Básico', 'time' => '15:00 - 16:30', 'teacher' => $professoresCreated[3]],
            ['name' => 'Inglês - Intermediário', 'time' => '16:45 - 18:15', 'teacher' => $professoresCreated[3]],
        ];

        $teamsCreated = [];
        foreach ($turmas as $turma) {
            $team = Team::create([
                'name' => $turma['name'],
                'time' => $turma['time'],
                'user_id' => $turma['teacher']->id,
            ]);
            $teamsCreated[] = $team;
        }

        // ===== 5. CRIAR ALUNOS E RELACIONADOS =====
        $alunos_por_turma = 6; // Menor número para não ficar muito pesado
        $responsavelIndex = 0;

        foreach ($teamsCreated as $team) {
            for ($i = 0; $i < $alunos_por_turma; $i++) {
                $student = Student::create([
                    'name' => fake()->firstName() . ' ' . fake()->lastName(),
                    'parent_name' => $responsaveisCreated[$responsavelIndex]['name'],
                    'phone' => '(11) 9' . fake()->numerify('####-####'),
                    'team_id' => $team->id,
                    'responsavel_id' => $responsaveisCreated[$responsavelIndex]->id,
                    'birth_date' => fake()->dateTimeBetween('-15 years', '-7 years'),
                    'fee' => fake()->randomElement([150.00, 200.00, 250.00]),
                    'due_day' => fake()->numberBetween(5, 25),
                    'active' => random_int(0, 100) > 15, // 85% ativo
                    'notes' => random_int(0, 100) > 70 ? fake()->sentence() : null,
                ]);

                // Rotaciona responsáveis
                $responsavelIndex = ($responsavelIndex + 1) % count($responsaveisCreated);

                // ===== 6. REGISTRAR PRESENÇA ÚLTIMOS 30 DIAS =====
                $now = now();
                for ($dia = 0; $dia < 30; $dia++) {
                    $data = $now->copy()->subDays($dia);
                    
                    // Pula fins de semana (sábado = 6, domingo = 0)
                    if ($data->dayOfWeek == 0 || $data->dayOfWeek == 6) {
                        continue;
                    }

                    // 90% de chance de marcar presença em dias úteis
                    if (random_int(0, 100) > 10) {
                        // 95% presente, 5% ausente
                        $presente = random_int(0, 100) > 5;

                        Attendance::create([
                            'student_id' => $student->id,
                            'date' => $data->toDateString(),
                            'present' => $presente,
                            'obs' => $presente ? null : 'Faltou',
                        ]);
                    }
                }

                // ===== 7. REGISTRAR PAGAMENTOS - 3 ÚLTIMOS MESES =====
                $months = [
                    ['year' => now()->year, 'month' => now()->month],
                    ['year' => now()->year, 'month' => now()->month - 1],
                    ['year' => now()->year, 'month' => now()->month - 2],
                ];

                foreach ($months as $period) {
                    // Ajusta mês/ano se negativo
                    if ($period['month'] <= 0) {
                        $period['month'] += 12;
                        $period['year'] -= 1;
                    }

                    $paid_at = null;
                    // 85% de chance de estar pago
                    if (random_int(0, 100) > 15) {
                        $paid_at = now()->copy()
                            ->year($period['year'])
                            ->month($period['month'])
                            ->day(min(now()->day, 28));
                    }

                    Payment::create([
                        'student_id' => $student->id,
                        'year' => $period['year'],
                        'month' => $period['month'],
                        'amount' => $student->fee,
                        'paid_at' => $paid_at,
                        'method' => $paid_at ? fake()->randomElement(['Pix', 'Transferência', 'Dinheiro']) : null,
                    ]);
                }
            }
        }

        // ===== MENSAGENS DE SUCESSO =====
        $this->command->info('✅ Database populada com sucesso!');
        $this->command->info('');
        $this->command->info('📊 Dados criados:');
        $this->command->info('  • 1 Admin');
        $this->command->info('  • 4 Professores');
        $this->command->info('  • 5 Responsáveis (Pais)');
        $this->command->info('  • 8 Turmas');
        $this->command->info('  • ' . (8 * $alunos_por_turma) . ' Alunos');
        $this->command->info('  • Presença dos últimos 30 dias');
        $this->command->info('  • Pagamentos dos últimos 3 meses');
        $this->command->info('');
        $this->command->info('🔑 Login de teste:');
        $this->command->info('  • Admin: admin@admin.com | Senha: password');
        $this->command->info('  • Professor: carlos.mat@escola.com | Senha: password');
        $this->command->info('  • Responsável: maria.silva@email.com | Senha: password');
    }
}