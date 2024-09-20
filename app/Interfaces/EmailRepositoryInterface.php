<?php

namespace App\Interfaces;

interface EmailRepositoryInterface 
{
    public function sendEmail($recipientEmail, $subject, $name, $messageContent);   
}