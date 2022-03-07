<?php

namespace App\Modules\LoginVerification;

use ClickSend\ApiException;
use Illuminate\Contracts\Foundation\Application;

class SmsProviderFactory
{
    public static function create(Application $application, string $provider): LoginVerificationInterface
    {
        return match($provider) {
            'clickSend' => $application->make(ApiException::class),
            'smsGateway' => $application->make(SmsGateway::class),
            'twilio' => $application->make(Twilio::class),
            default => $application->make(NullLoginVerification::class)
        };
    }
}
