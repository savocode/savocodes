<?php

namespace App\Classes;

class RijndaelEncryption
{
    /**
     * Singleton pattern instance
     */
    private static $instance;

    /**
     * Auto generation key
     */
    private static $key;

    /**
     * MCrypt mode
     */
    protected $mcrypt_mode = MCRYPT_MODE_CBC;

    public static function decrypt($encrypted)
    {
        if ( empty($encrypted) ) {
            return '';
        }

        $self = self::getInstance();
        $key = $self->generateKey();

        $decrypted = trim($self->_decrypt($key, constants('global.encryption.InitializationVector'), $encrypted));

        // Remove non-ASCII Characters, may be its decryption flaw while porting this decrpytion to PHP
        $decrypted = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $decrypted);

        return $decrypted;
        // return in_array(strtoupper(mb_detect_encoding($decrypted)), ['ASCII']) ? $decrypted : '';
    }

    public static function encrypt($value, $base64Encode=true)
    {
        $self = self::getInstance();
        $key  = $self->generateKey();

        $encrypted = $self->_encrypt($key, constants('global.encryption.InitializationVector'), $value);
        // info(['Enc' => [$value => ($base64Encode ? base64_encode($encrypted) : $encrypted)]]);

        return $base64Encode ? base64_encode($encrypted) : $encrypted;
    }

    private static function getInstance()
    {
        if ( null === self::$instance ) {
            self::$instance = new static;
        }

        return self::$instance;
    }

    private function getKeySize()
    {
        return intval(constants('global.encryption.keySize'));
    }

    private function getKeyLength()
    {
        return $this->getKeySize() / 8;
    }

    private function getMcryptCipher()
    {
        return $this->getKeySize() === 128 ? MCRYPT_RIJNDAEL_128 : MCRYPT_RIJNDAEL_256;
    }

    private function generateKey()
    {
        if ( null == self::$key ) {
            self::$key = $this->PBKDF1(
                constants('global.encryption.passPhraseKey'),
                constants('global.encryption.SaltValueKey'),
                constants('global.encryption.NoOfIteration'),
                $this->getKeyLength()
            );
        }

        return self::$key;
    }

    protected function _decrypt($key, $iv, $encrypted)
    {
        return mcrypt_decrypt($this->getMcryptCipher(), $key, base64_decode($encrypted), $this->mcrypt_mode, $iv);
    }

    protected function _encrypt($key, $iv, $password)
    {
        $block = mcrypt_get_block_size($this->getMcryptCipher(), MCRYPT_MODE_ECB);
        $padding = $block - (strlen($password) % $block);
        $password .= str_repeat(chr($padding), $padding);
        return mcrypt_encrypt($this->getMcryptCipher(), $key, $password, $this->mcrypt_mode, $iv);
    }

    protected function PBKDF1($pass, $salt, $iterations, $lengths)
    {
        static $base;
        static $extra;
        static $extracount = 0;
        static $hashno;
        static $state = 0;

        if ($state == 0) {
            $hashno = 0;
            $state = 1;

            $key = $pass . $salt;
            $base = sha1($key, true);
            for ($i = 2; $i < $iterations; $i++) {
                $base = sha1($base, true);
            }
        }

        $result = "";

        if ($extracount > 0) {
            $rlen = strlen($extra) - $extracount;
            if ($rlen >= $lengths) {
                $result = substr($extra, $extracount, $lengths);
                if ($rlen > $lengths) {
                    $extracount += $lengths;
                } else {
                    $extra = null;
                    $extracount = 0;
                }
                return $result;
            }
            $result = substr($extra, $rlen, $rlen);
        }

        $current = "";
        $clen = 0;
        $remain = $lengths - strlen($result);
        while ($remain > $clen) {
            if ($hashno == 0) {
                $current = sha1($base, true);
            } else if ($hashno < 1000) {
                $n = sprintf("%d", $hashno);
                $tmp = $n . $base;
                $current .= sha1($tmp, true);
            }
            $hashno++;
            $clen = strlen($current);
        }

        // $current now holds at least as many bytes as we need
        $result .= substr($current, 0, $remain);

        // Save any left over bytes for any future requests
        if ($clen > $remain) {
            $extra = $current;
            $extracount = $remain;
        }

        return $result;
    }
}
