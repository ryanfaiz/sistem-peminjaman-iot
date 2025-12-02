<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LoanFactory extends Factory
{
    public function definition(): array
    {
        $loanDate = $this->faker->dateTimeBetween('-3 months', 'now');
        $dueDate = Carbon::parse($loanDate)->addDays($this->faker->numberBetween(7, 30));
        
        $status = $this->faker->randomElement(['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan']);

        $data = [
            'user_id' => User::factory(),
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'status' => $status,
            'purpose' => $this->faker->sentence(),
            'admin_notes' => null,
            'consent' => true,
        ];

        if ($status === 'disetujui' || $status === 'dipinjam') {
            $admin = User::where('role', 'admin')->inRandomOrder()->first();
            
            $data['approved_by'] = $admin ? $admin->id : null;
            $data['approved_at'] = Carbon::parse($loanDate)->addDays($this->faker->numberBetween(1, 3));
        }

        if ($status === 'dikembalikan') {

            $data['approved_by'] = User::where('role', 'admin')->inRandomOrder()->first()->id ?? null;
            $data['approved_at'] = Carbon::parse($loanDate)->addDays($this->faker->numberBetween(1, 3));
            
            $returnedAt = $this->faker->dateTimeBetween($data['approved_at'], Carbon::now());

            $data['returned_at'] = $returnedAt;

            if (Carbon::parse($returnedAt)->greaterThan($dueDate)) {
                $data['admin_notes'] = 'Dikembalikan terlambat ' . Carbon::parse($returnedAt)->diffInDays($dueDate) . ' hari.';
            }
        }
        
        if ($status === 'ditolak') {
            $data['approved_by'] = User::where('role', 'admin')->inRandomOrder()->first()->id ?? null;
            $data['approved_at'] = Carbon::parse($loanDate)->addDays($this->faker->numberBetween(1, 3));
            $data['admin_notes'] = 'Permintaan ditolak karena stok tidak mencukupi.';
        }

        return $data;
    }

    public function pending(): Factory
    {
        return $this->state(fn () => [
            'status' => 'diajukan',
            'approved_by' => null,
            'approved_at' => null,
            'returned_at' => null,
            'admin_notes' => null,
        ]);
    }
}