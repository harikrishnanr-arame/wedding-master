<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please seed users first.');
            return;
        }

        $providers = ['UPI', 'Card', 'Net Banking', 'Wallet', 'COD'];
        $statuses  = ['paid', 'pending', 'failed'];

        foreach ($users as $user) {
            // Create 3–5 payments per user
            $numPayments = rand(3, 5);

            for ($i = 0; $i < $numPayments; $i++) {
                Payment::create([
                    'user_id' => $user->id,
                    'user_template_id' => null, // or assign if templates exist
                    'amount' => rand(500, 10000),
                    'currency' => 'INR',
                    'payment_provider' => $providers[array_rand($providers)],
                    'status' => $statuses[array_rand($statuses)],
                    'paid_at' => now()->subDays(rand(0, 30)),
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('Payments seeded successfully!');
    }
}
