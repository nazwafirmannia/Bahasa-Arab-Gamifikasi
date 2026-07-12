<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendLearningReminder extends Command
{
    protected $signature = 'reminder:study';

    protected $description = 'Kirim pengingat belajar';

    public function handle()
    {
        $users = User::with('stat')
            ->where('role', 'user')
            ->get();

        foreach ($users as $user) {

            if (!$user->stat) {
                continue;
            }

            if (!$user->stat->last_activity) {
                continue;
            }

            $lastActivity = $user->stat->last_activity;

            // Testing: kirim jika tidak aktif 30 detik
            if ($lastActivity->diffInSeconds(now()) < 30) {
                continue;
            }

            try {

                $response = Http::withHeaders([
                    'accept' => 'application/json',
                    'api-key' => env('BREVO_API_KEY'),
                    'content-type' => 'application/json',
                ])->post('https://api.brevo.com/v3/smtp/email', [

                    'sender' => [
                        'name' => env('MAIL_FROM_NAME'),
                        'email' => env('MAIL_FROM_ADDRESS'),
                    ],

                    'to' => [
                        [
                            'email' => $user->email_user,
                            'name' => $user->name_user,
                        ]
                    ],

                    'subject' => 'Arabic Quest - Pengingat Belajar',

                    'htmlContent' => view('emails.learning-reminder', [
                        'user' => $user
                    ])->render(),

                ]);

                if ($response->successful()) {

                    $user->stat->update([
                        'reminder_sent_at' => now(),
                    ]);

                    $this->info("Berhasil kirim ke {$user->email_user}");

                } else {

                    Log::error('BREVO ERROR', [
                        'user' => $user->email_user,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);

                    $this->error($response->body());

                }

            } catch (\Throwable $e) {

                Log::error('BREVO EXCEPTION', [
                    'user' => $user->email_user,
                    'message' => $e->getMessage(),
                ]);

                $this->error($e->getMessage());

            }

        }

        return Command::SUCCESS;
    }
}