<?php

namespace App\Repositories;

use App\Interfaces\EmailRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailRepository implements EmailRepositoryInterface 
{
    public function sendEmail($recipientEmail, $subject, $name, $messageContent)
    {
        $data = [
            'subject' => $subject,
            'name'    => $name,
            'messageContent' => $messageContent, 
        ];

        Log::info('email data', $data);

        // Send the email using Blade view and pass the data
        Mail::send('emails.email', $data, function ($emailMessage) use ($recipientEmail, $subject) {
            $emailMessage->to($recipientEmail)
                         ->subject($subject);
        });

        return true;
    }
}
