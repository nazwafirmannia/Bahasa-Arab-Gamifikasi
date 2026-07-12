<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\LearningReminderMail;
use Illuminate\Support\Facades\Mail;
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

            if ($user->stat->last_activity->diffInDays(now()) < 3) {
                continue;
            }

            try {

                Mail::to($user->email_user)
                    ->send(new LearningReminderMail($user));

                $user->stat->update([
                    'reminder_sent_at' => now(),
                ]);

                $this->info("Berhasil kirim ke {$user->email_user}");

            } catch (\Throwable $e) {

                Log::error('MAIL ERROR', [
                    'user' => $user->email_user,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                $this->error($e->getMessage());
            }
        }

        return self::SUCCESS;
    }
}