<?php

use App\Modules\LoginVerification\ClickSend;
use App\Modules\LoginVerification\NullLoginVerification;

return [
    'provider' => env('SMS_PROVIDER', 'local'),

    'twilio' => [
        'sid' => env('TWILIO_SID', ''),
        'token' => env('TWILIO_TOKEN', ''),
        'service_id' => env('TWILIO_SERVICE_ID', ''),
        'from' => '+15854969023'
    ],

    'clickSend' => [
        'username' => env('CLICK_SEND_USERNAME', ''),
        'password' => env('CLICK_SEND_PASSWORD', '')
    ],

    'smsGateway' => [
        'token' => env("SMS_GATEWAY_TOKEN", '')
    ],


];
