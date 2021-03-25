<?php
namespace Frunts\SimpleCrypter;

use Frunts\SimpleCrypter\Exceptions\CryptException;
use Illuminate\Config\Repository;
use Frunts\SimpleCrypter\Services\Core\Interfaces\CrypterService;

class Crypter
{
    /**
     * 配置信息
     *
     * @var array
     */
    protected $configs;
    /**
     * 当前加密驱动
     *
     * @var string
     */
    protected $cryptDriver;
    /**
     * 当前加密驱动配置信息
     *
     * @var array
     */
    protected $driverOption;
    /**
     * 加密方式 [字符串, 公私钥对儿]
     *
     * @var string
     */
    protected $cryptType;
    /**
     * 加密算法['AES-128-CBC', 'AES-256-CBC', 'RSA', ...]
     *
     * @var string
     */
    protected $cryptMethod;
    /**
     * 加密约定字符串组 ['key' => '', 'iv' => '']
     * openssl 证书信息 || ['publicKey' => '', 'privateKey' => '']
     * 
     * @var array
     */
    protected $cryptKeys;
    /**
     * openssl 证书配置方式 || [file_path, content]
     *
     * @var string
     */
    protected $cretType;
    /**
     * 加、解密类
     *
     * @var CrypterService
     */
    protected $crypter;
    /**
     * 初始化
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->cryptDriver = $config->get('simpleCrypter.default');
        $this->configs = $config->get('simpleCrypter');
        $this->setCryptInfo();
    }
    /**
     * 设置加密配置项
     *
     * @param string $driver
     */
    public function setCryptInfo(string $driver='')
    {
        $driver = $driver ?? $this->cryptDriver;
        if($this->isConfigation($this->configs, $driver)){
            $this->driverOption = $this->configs['drivers'][$driver];
            $this->setCryptType();
            $this->setCryptMethod();
            $this->setCryptCore();
            $this->loadCryptProvider();
        }
    }
    /**
     * 实例化加、解密类
     *
     * @return CrypterService
     */
    public function loadCryptProvider(): CrypterService
    {
        $provider = "\\Frunts\\SimpleCrypter\\Services\\Core\\".$this->driverOption['crypt_svs'].'CrypterProvider';
        return new $provider($this->cryptType, $this->cryptKeys);
    }
    /**
     * 设置加密算法依赖信息
     */
    private function setCryptType()
    {
        if($this->isConfigation($this->configs['drivers'], 'crypt_type', $this->cryptDriver)){
            $this->cryptType = $this->driverOption['crypt_type'];
        }
    }
    /**
     * 设置加密算法类型
     */
    private function setCryptMethod()
    {
        if($this->isConfigation($this->configs['drivers'], 'crypt_method', $this->cryptDriver)){
            $this->cryptMethod = $this->driverOption['crypt_method'];
        }
    }
    /**
     * 设置加密算法密钥信息
     */
    private function setCryptCore()
    {
        if($this->driverOption['crypt_type'] === 'kv'){
            $this->cryptKeys = $this->configs['ksv'][$this->driverOption['kv']];
        }
        if($this->driverOption['crypt_type'] === 'crets'){
            $this->cryptKeys = $this->configs['crets_bags'][$this->driverOption['crets_bag']];
            if($this->driverOption['crets_type'] === 'file'){
                $this->cryptKeys = [
                    'publicKey' => $this->getCertificatesContents($this->cryptKeys['publicKey'], 'public'),
                    'privateKey' => $this->getCertificatesContents($this->cryptKeys['privateKey'], 'private')
                ];
            }
        }
    }
    /**
     * 判断配置项是否存在
     *
     * @param string $rex
     * @param string $rexKey
     * @return boolean
     */
    private function isConfigation(array $configOptions, string $rex, string $rexKey='drivers'): bool
    {
        if(!isset($configOptions[$rexKey][$rex]) || $configOptions[$rexKey][$rex]){
            throw new CryptException("Nonexistent configuration item! {$rexKey} {$rex}");
        }
        return true;
    }
    /**
     * 读取证书内容
     *
     * @param string $certificateFile
     */
    private function getCertificatesContents(string $certificateFile, string $type)
    {
        $handle = fopen($certificateFile, 'r');
        $contents = fread($handle, filesize($certificateFile));
        return $type === 'public' ? openssl_pkey_get_public($contents) : openssl_pkey_get_private($contents) ;
    }
}