<?php

namespace Garsaud\CryptStreamAES;

class AES
{
    public function __construct(
        protected Length $keyLength = Length::AES128,
    ) {
    }

    public function encrypt(string $key, $inputStream, $outputStream): void
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        fwrite($outputStream, str_repeat('_', 32));
        fwrite($outputStream, mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM));
        fwrite($outputStream, $esalt = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM));
        fwrite($outputStream, $iv = mcrypt_create_iv($iv_size, MCRYPT_DEV_URANDOM));
        $ekey = hash_pbkdf2('sha256', $key, $esalt, 1000, $this->keyLength->value, true);
        $opts = ['mode' => 'cbc', 'iv' => $iv, 'key' => $ekey];
        stream_filter_append($outputStream, 'mcrypt.rijndael-128', STREAM_FILTER_WRITE, $opts);
        $infilesize = 0;
        while (! feof($inputStream)) {
            $block = fread($inputStream, 8192);
            $infilesize += strlen($block);
            fwrite($outputStream, $block);
        }
        $block_size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $padding = $block_size - ($infilesize % $block_size);
        fwrite($outputStream, str_repeat(chr($padding), $padding));
    }

    public function decrypt(string $key, $inputStream, $outputStream): void
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        fread($inputStream, 32 + $iv_size);
        $esalt = fread($inputStream, $iv_size);
        $iv = fread($inputStream, $iv_size);
        $ekey = hash_pbkdf2('sha256', $key, $esalt, 1000, $this->keyLength->value, true);
        $opts = ['mode' => 'cbc', 'iv' => $iv, 'key' => $ekey];
        stream_filter_append($inputStream, 'mdecrypt.rijndael-128', STREAM_FILTER_READ, $opts);
        while (! feof($inputStream)) {
            $block = fread($inputStream, 8192);
            if (feof($inputStream)) {
                $padding = ord($block[strlen($block) - 1]);
                $block = substr($block, 0, 0 - $padding);
            }
            fwrite($outputStream, $block);
        }
    }
}
