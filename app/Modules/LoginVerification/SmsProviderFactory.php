<?php

namespace App\Modules\LoginVerification;

use Illuminate\Contracts\Foundation\Application;

class SmsProviderFactory
{
    public static function create(Application $application, string $provider): LoginVerificationInterface
    {
        return match($provider) {
            'clickSend' => $application->make(ClickSend::class),
            'smsGateway' => $application->make(SmsGateway::class),
            'twilio' => $application->make(Twilio::class),
            default => $application->make(NullLoginVerification::class)
        };
    }
}
