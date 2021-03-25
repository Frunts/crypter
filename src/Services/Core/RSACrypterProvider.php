<?php
namespace Frunts\SimpleCrypter\Services\Core;

use Frunts\SimpleCrypter\Services\Core\Interfaces\CrypterService;

class AESCrypter extends CrypterService
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
		$publicKey = $this->keys['publicKey'];
		$encrypting = $serialize ? serialize($encryption) : $encryption;
        $res = openssl_public_encrypt($encrypting, $data, $publicKey) ? base64_encode($data) : null; 
        return $res;
    }
    /**
     * 解密
     *
     * @param string $decryption
     */
    public function decrypt(string $decryption)
    {
        $privateKey = $this->keys['privateKey']; 
        $encrypted = base64_decode($decryption);
        $mydata = openssl_private_decrypt($encrypted, $decrypted, $privateKey) ? $decrypted : '';
        return $mydata;
    }
}