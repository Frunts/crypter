<?php
namespace Frunts\SimpleCrypter\Facades;

use Illuminate\Support\Facades\Facade;

class Crypter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'crypter';
    }
}