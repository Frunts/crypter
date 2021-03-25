<?php
namespace Frunts\SimpleCrypter\Services\Core\Interfaces;

class CrypterService implements CryptInterface
{
    protected $keys = [];

    protected $cryptType = '';

    public function encrypt(string $encryption, $serialize = false)
    {   
    }

    public function decrypt(string $decryption)
    {  
    }
}