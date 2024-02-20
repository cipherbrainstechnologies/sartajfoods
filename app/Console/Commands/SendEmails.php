<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMailable;
use Illuminate\Support\Facades\DB;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to users to reset password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       
         // Get 10 users
        //  $users = User::where('email','mukesh@silverwebbuzz.com')->get();//User::take(10)->get();
        //  if(!empty($users)){
            // Loop through each user and send an email
        User::whereNotNull('email')->chunk(10, function ($users) {
            foreach ($users as $user) {
                $token = rand(1000, 9999);
                DB::table('password_resets')->updateOrInsert(['email_or_phone' => $user->email], [
                    'email_or_phone' => $user->email,
                    'token' => $token,
                    'created_at' => now(),
                ]);
                Mail::to($user->email)->queue(new ResetPasswordMailable($user,$token));
                // Log a message
                info('Email sent successfully for user ' . $user->id);
            }
        });
    }
}
