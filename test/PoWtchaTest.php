<?php

namespace Btccom\PoWtcha\Tests;

use Btccom\PoWtcha\PoWtcha;

class PoWtchaTest extends \PHPUnit_Framework_TestCase {

    public function testHash() {
        $vectors = [
            ['fe23fe23fe23fe23fe23', 0, 17257, '00083a05d3e663172298d78ac73ee9eff7655604932e227c8dfd19710729a301'],
            ['fe23fe23fe23fe23fe23', 0, 18680, '00037ea7ae8fc8c43593dc49e0b35f81773390f3112f7f34ad56eddc094e7b01'],
            ['fe23fe23fe23fe23fe23', 0, 282381, '00001f4ff0639dc8ae6bd5929750d4a3ef5d1b0146c5d5d229b9db9fbe8eadcb'],
            ['fe23fe23fe23fe23fe23', 282381, 282381, '4638a7d8e7d55e1d48fdcf3695c693b7c1b73df93c583ba63cfa8da091833b14'],
            ['fe23fe23', 0, 17257, '3a5746cdf2fda8f7928f9e60a2704ee29ae81206eff8e2394e9efeedd736f175'],
            ['fe23fe23fe23fe23fe23fe23fe23fe23', 0, 17257, '295aaf021ab3d76cc2955f5185aa4ce5dec2b436dc5be91bddf26b06d3f84547'],
        ];

        foreach ($vectors as $vector) {
            $salt = $vector[0];
            $extraNonce = $vector[1];
            $nonce = $vector[2];
            $hash = $vector[3];

            $this->assertEquals($hash, PoWtcha::hash($salt, $extraNonce, $nonce));
        }
    }

    public function testValidate() {
        $vectors = [
            ['00083a05d3e663172298d78ac73ee9eff7655604932e227c8dfd19710729a301', 0x000fffff, 0x00057fff],
            ['00037ea7ae8fc8c43593dc49e0b35f81773390f3112f7f34ad56eddc094e7b01', 0x00057fff, 0x0000ffff],
            ['00001f4ff0639dc8ae6bd5929750d4a3ef5d1b0146c5d5d229b9db9fbe8eadcb', 0x0000ffff, null],
            ['4638a7d8e7d55e1d48fdcf3695c693b7c1b73df93c583ba63cfa8da091833b14', null, 0x0fffffff]
        ];

        foreach ($vectors as $vector) {
            $hash = $vector[0];
            $shouldMatchTarget = $vector[1];
            $shouldNotMatchTarget = $vector[2];

            if ($shouldMatchTarget) {
                $this->assertTrue(PoWtcha::validateTarget($hash, $shouldMatchTarget));
            }

            if ($shouldNotMatchTarget) {
                $this->assertTrue(!PoWtcha::validateTarget($hash, $shouldNotMatchTarget));
            }
        }
    }
}
