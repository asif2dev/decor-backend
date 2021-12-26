<?php

namespace Tests\Unit;

use App\Support\PhoneNumberHelper;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testPhoneNumber()
    {
        $phone = '+201117181875';
        $this->assertTrue(PhoneNumberHelper::isValid($phone));

        $this->assertEquals('+201117181875', PhoneNumberHelper::getFormattedPhone($phone));
    }
}
