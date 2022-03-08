<?php

namespace App\Modules\LoginVerification;

use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

class Twilio implements LoginVerificationInterface
{
    private Client $client;

    public function __construct(private LoggerInterface $logger)
    {
        $this->client = new Client(
            config('sms.twilio.sid'),
            config('sms.twilio.token')
        );
    }


    public function sendLoginMessage(string $phone, string $code): bool
    {
        try {
            $this->client->messages->create(
                $phone,
                [
                    'from' => config('sms.twilio.from'),
                    'body' => " رمز التحقق الخاص بك هو " . $code
                ]
            );

            return true;
        } catch (\Throwable $exception) {
            $this->logger->error('SmsGateway: failed to send sms', [
                'message' => $exception->getMessage(),
                'exception' => $exception
            ]);

            return false;
        }
    }
}
