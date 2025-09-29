<?php
namespace App\Helpers;

class General
{
    public static function encryptData($value)
    {
        $encrypter = CustomerEncrypter::getInstance()->getEncrypter();

        return $encrypter->encryptString($value);
    }

    public static function decryptData($value)
    {
        if ($value) {
            $encrypter = CustomerEncrypter::getInstance()->getEncrypter();

            return $encrypter->decryptString($value);
        }

        return '';
    }
}
