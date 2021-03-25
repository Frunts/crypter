<?php
namespace Frunts\SimpleCrypter\Services\Core;

use Frunts\SimpleCrypter\Services\Core\Interfaces\CrypterService;

class AESCrypterProvider extends CrypterService
{
    /**
     * 初始化加解密类
     *
     * @param string $cryptType
     * @param array $keys
     */
    public function __construct(string $cryptType, array $keys)
    {
        $this->cryptType = $cryptType;
        $this->keys = $keys;
    }
    /**
     * 加密
     *
     * @param string $encryption
     * @param boolean $serialize
     */
    public function encrypt(string $encryption, $serialize = false)
    {
		$encrypting = $serialize ? serialize($encryption) : $encryption;
		$encrypted = openssl_encrypt($encrypting, $this->cryptType, $this->keys['key'], OPENSSL_RAW_DATA, $this->keys['iv']);
		return base64_encode($encrypted);
    }
    /**
     * 解密
     *
     * @param string $decryption
     */
    public function decrypt(string $decryption)
    {
		$encrypted = base64_decode($decryption);
		$decrypted = openssl_decrypt($encrypted, $this->cryptType, $this->keys['key'], OPENSSL_RAW_DATA, $this->keys['iv']);
		return trim($decrypted);
    }
}