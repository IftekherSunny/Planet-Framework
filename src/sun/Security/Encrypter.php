<?php

namespace Sun\Security;

use Exception;

class Encrypter
{
    /**
     * IV size
     *
     * @var int
     */
    protected $ivSize;

    /**
     * Key size
     *
     * @var int
     */
    protected $keySize;

    /**
     * Cipher method name
     *
     * @var string
     */
    protected $cipher;

    /**
     * Encryption key
     *
     * @var string
     */
    protected $key;

    public function __construct()
    {
        $this->ivSize = 16;

        $this->keySize = 32;

        $this->method = 'AES-256-CBC';

        $this->key = $this->getKey();
    }


    /**
     * To encrypt data
     *
     * @param $data
     *
     * @return string
     */
    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes($this->getSize());

        $value = openssl_encrypt(serialize($data), $this->method, $this->key, 0, $iv);

        $mac = $this->hash($iv = base64_encode($iv), $value);

        return base64_encode(json_encode(compact('iv', 'value', 'mac')));
    }

    /**
     * To decrypt data
     *
     * @param $data
     *
     * @return mixed|string
     * @throws Exception
     */
    public function decrypt($data)
    {
        $json = base64_decode($data);
        $data = json_decode($json);

        // check mac
        if ($this->hashCheck($data->iv, $data->value, $data->mac)) {

            $iv = base64_decode($data->iv);

            $data = openssl_decrypt($data->value, $this->method, $this->key, 0, $iv);

            return unserialize($data);

        }

        throw new Exception('Mac not match.');
    }

    /**
     * To check mac
     *
     * @param $iv
     * @param $value
     * @param $mac
     *
     * @return bool
     */
    protected function hashCheck($iv, $value, $mac)
    {
        $salt = $this->keyGenerate();

        $mainMac = hash_hmac('sha256', $this->hash($iv, $value), $salt);

        $checkMac = hash_hmac('sha256', $mac, $salt);

        if ($mainMac === $checkMac) {
            return true;
        }

        return false;
    }

    /**
     * To create hash mac
     *
     * @param $iv
     * @param $value
     *
     * @return string
     */
    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
     * To get IV size
     *
     * @return int
     */
    protected function getSize()
    {
        return $this->ivSize;
    }

    /**
     * Get the key
     *
     * @throws Exception
     * @return string
     */
    protected function getKey()
    {
        $key = config('app.key');

        if(mb_strlen($key, '8bit') !== $this->keySize) {
            throw new Exception("Key length must be {$this->keySize} characters.");
        }

        return $key;
    }


    /**
     * To generate new key
     *
     * @return string
     */
    protected function keyGenerate()
    {
        $bytes = openssl_random_pseudo_bytes($this->keySize, $strong);

        if ($bytes !== false && $strong !== false) {
            $string = '';
            while (($len = strlen($string)) < $this->keySize) {
                $length = $this->keySize - $len;

                $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $length);
            }

            return $string;
        }
    }
}