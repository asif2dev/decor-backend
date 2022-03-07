<?php

use App\Modules\LoginVerification\ClickSend;
use App\Modules\LoginVerification\NullLoginVerification;

return [
    'provider' => env('SMS_PROVIDER', 'local')
];
