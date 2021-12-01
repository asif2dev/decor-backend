<?php


namespace App\Modules\LoginVerification\ClickSend;


use App\Modules\LoginVerification\LoginVerificationInterface;
use ClickSend\Api\SMSApi;
use ClickSend\Configuration;
use ClickSend\Model\SmsMessage;
use ClickSend\Model\SmsMessageCollection;
use GuzzleHttp\Client;

class ClickSend implements LoginVerificationInterface
{
    private SMSApi $smsApi;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername('mohamed.n.haleem@gmail.com')
            ->setPassword('257E9F48-1301-71A9-21B6-DFD39E81B67B');

        $this->smsApi = new SMSApi(new Client(), $config);
    }

    public function sendLoginMessage(string $phone, string $code): bool
    {
        $msg = new SmsMessage();
        $msg->setBody(" رمز التحقق الخاص بك هو " . $code);
        $msg->setTo($phone);
        $msg->setSource("Decor Test Webapp");

        $smsMessages = new SmsMessageCollection();
        $smsMessages->setMessages([$msg]);

        return $this->smsApi->smsSendPost($smsMessages);
    }
}
