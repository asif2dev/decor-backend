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

    public function verify(string $phone, string $code): bool
    {
        try {
            $response = $this->client
                ->verify
                ->v2
                ->services(config('sms.twilio.service_id'))
                ->verificationChecks
                ->create($code, ['to' => $phone]);

            return $response->status === 'approved';
        } catch (\Throwable $exception) {
            $this->logger->error('Twilio: failed to send sms', [
                'message' => $exception->getMessage(),
                'exception' => $exception
            ]);

            return false;
        }
    }

    public function sendLoginMessage(string $phone, ?string $code = null): bool
    {
        try {
            $response = $this->client
                ->verify
                ->v2
                ->services(config('sms.twilio.service_id'))
                ->verifications
                ->create($phone, 'sms');

            return $response->status === 'pending';
        } catch (\Throwable $exception) {
            $this->logger->error('Twilio: failed to send sms', [
                'message' => $exception->getMessage(),
                'exception' => $exception
            ]);

            return false;
        }
    }
}
