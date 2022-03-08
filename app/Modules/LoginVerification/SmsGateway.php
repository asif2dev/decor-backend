<?php

namespace App\Modules\LoginVerification;

use Psr\Log\LoggerInterface;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\ApiException;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class SmsGateway implements LoginVerificationInterface
{
    private MessageApi $messageApi;

    public function __construct(private LoggerInterface $logger)
    {
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', config('sms.smsGateway.token'));
        $apiClient = new ApiClient($config);
        $this->messageApi = new MessageApi($apiClient);
    }

    public function sendLoginMessage(string $phone, string $code): bool
    {
        $sendMessageRequest1 = new SendMessageRequest([
            'phoneNumber' => $phone,
            'message' => " رمز التحقق الخاص بك هو " . $code,
            'deviceId' => 1
        ]);

        try {
            return (bool) $this->messageApi->sendMessages([$sendMessageRequest1]);
        } catch (ApiException $exception) {
            $this->logger->error('SmsGateway: failed to send sms', [
                'message' => $exception->getMessage(),
                'exception' => $exception
            ]);

            return false;
        }
    }
}
