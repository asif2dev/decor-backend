<?php


namespace App\Modules\LoginVerification;


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
            ->setUsername(config('sms.clickSend.username'))
            ->setPassword(config('sms.clickSend.password'));

        $this->smsApi = new SMSApi(new Client(), $config);
    }

    public function sendLoginMessage(string $phone, string $code): bool
    {
        $msg = new SmsMessage();
        $msg->setBody(" رمز التحقق الخاص بك هو " . $code);
        $msg->setTo($phone);
        $msg->setSource("نص تشتطيب");

        $smsMessages = new SmsMessageCollection();
        $smsMessages->setMessages([$msg]);

        return $this->smsApi->smsSendPost($smsMessages);
    }
}
