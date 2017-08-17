<?php

namespace Btccom\PoWtcha;

class PoWtcha {
    public static function validateTarget($hash, $target) {
        $hash = substr(str_repeat("0", 64) . $hash, -64); // zero pad cuz PHP likes to eat the 0s
        $first = substr($hash, 0, 8);
        $first = hexdec($first);

        return $first <= $target;
    }

    public static function hash($salt, $extraNonce, $nonce) {
        $input = $salt;
        $input .= substr("00000000" . dechex($extraNonce), -8); // extraNonce

        $inputLen = ceil(strlen($input) / 2 / 4);

        $input = substr($input . str_repeat("0", $inputLen * 2 * 4), 0, $inputLen * 2 * 4); // pad
        $input .= substr("00000000" . dechex($nonce), -8); // nonce

        return hash("sha256", hex2bin($input));
    }
}
