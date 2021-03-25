<?php
namespace Frunts\SimpleCrypter\Services\Core\Interfaces;

interface CryptInterface
{
    public function encrypt(string $encryption, $serialize = false);

    public function decrypt(string $decryption);
}