<?php

/**
 * This file was created by the developers from Infifni.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://infifnisoftware.ro and write us
 * an email on contact@infifnisoftware.ro.
 */

declare(strict_types=1);

namespace Infifni\SyliusEuPlatescPlugin\Utils;

class EuPlatescHelper
{
    private static function hmacsha1(string $key, string $data): string
    {
        $blocksize = 64;
        $hashfunc  = 'md5';

        if(strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }

        $key  = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);

        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));

        return bin2hex($hmac);
    }

    public static function macFormat(array $data, string $key): string
    {
        $str = '';
        foreach($data as $d) {
            if(null === $d || '' === $d) {
                $str .= '-';
            } else {
                $str .= strlen((string) $d) . $d;
            }
        }

        $key = pack('H*', $key);

        return self::hmacsha1($key, $str);
    }
}