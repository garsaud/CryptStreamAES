<?php

namespace Garsaud\CryptStreamAES\Tests;

use Garsaud\CryptStreamAES\AES;
use Garsaud\CryptStreamAES\Length;
use PHPUnit\Framework\TestCase;

class AES256Test extends TestCase
{
    /**
     * @return resource
     */
    protected function createFile(int $size, string $repeatedChar, int $buffer = 100): mixed
    {
        $file = tmpfile();
        while ($size -= $buffer) {
            fwrite($file, str_repeat($repeatedChar, $buffer));
        }
        rewind($file);
        return $file;
    }

    public function testEncryptDecrypt()
    {
        $key = 'B374A26A71490437AA024E4FADD5B497FDFF1A8EA6FF12F6FB65AF2720B59CCF';
        $bigFile = $this->createFile(209715200, 'a'); // 200 MiB of repeated 'a'
        $bigFileMd5 = '28d37fc59ecd3aa933b56ccdc2521478';

        $aesEncrypt = new AES(Length::AES256);

        $input = $bigFile;

        $this->assertEquals(
            $bigFileMd5,
            md5_file(stream_get_meta_data($input)['uri'])
        );

        $encryptedFile = tmpfile();

        $aesEncrypt->encrypt($key, $input, $encryptedFile);

        rewind($encryptedFile);

        $this->assertNotEquals(
            $bigFileMd5,
            md5_file(stream_get_meta_data($encryptedFile)['uri'])
        );

        $decryptedFile = tmpfile();

        $aesEncrypt->decrypt($key, $encryptedFile, $decryptedFile);

        rewind($decryptedFile);

        $this->assertEquals(
            $bigFileMd5,
            md5_file(stream_get_meta_data($decryptedFile)['uri'])
        );
    }
}
