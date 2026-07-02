<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Mail\LearningReminderMail;
use Illuminate\Support\Facades\Mail;

class SendLearningReminder extends Command
{
    protected $signature = 'reminder:study';

    protected $description = 'Kirim pengingat belajar kepada user yang tidak aktif 3 hari';

    public function handle()
    {
        $users = User::with('stat')
            ->where('role', 'user')
            ->get();
    
        foreach ($users as $user) {
    
            if (!$user->stat) {
                continue;
            }
    
            $lastActivity = $user->stat->last_activity;
    
            if (!$lastActivity) {
                continue;
            }
    
            if ($lastActivity->diffInDays(now()) >= 3) {
    
                if (!$user->stat->reminder_sent_at) {
    
                    Mail::to($user->email_user)
                        ->send(new LearningReminderMail($user));
    
                    $user->stat->update([
                        'reminder_sent_at' => now()
                    ]);
    
                    $this->info(
                        "Reminder dikirim ke {$user->email_user}"
                    );
                }
            }
        }
    
        return Command::SUCCESS;
    }
}