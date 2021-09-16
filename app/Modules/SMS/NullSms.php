<?php


namespace App\Modules\SMS;


class NullSms implements SMSInterface
{

    public function sendLoginMessage(string $phone, string $code): bool
    {
        return true;
    }
}
