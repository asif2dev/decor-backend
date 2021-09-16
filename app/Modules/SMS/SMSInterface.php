<?php


namespace App\Modules\SMS;


interface SMSInterface
{
    public function sendLoginMessage(string $phone, string $code): bool;
}
