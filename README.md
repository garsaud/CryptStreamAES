# CryptStreamAES

[![GitHub CI](https://github.com/garsaud/CryptStreamAES/actions/workflows/php.yml/badge.svg)](https://github.com/garsaud/CryptStreamAES/actions)

This package encrypts and decrypts resources (streams) using AES 128, 192 or 256 using mcrypt.

It operates on resources (local or remote obtained via `fopen(…)`) in chunks, making it possible to process very large contents without exceeding the memory limit.

## Installation

```bash
composer require garsaud/cryptstreamaes
```

## Usage

```php
use Garsaud\CryptStreamAES\AES;
use Garsaud\CryptStreamAES\Length;

$aes = new AES(Length::AES256);

$aes->encrypt(
    key: 'B374A26A71490437AA024E4FADD5B497FDFF1A8EA6FF12F6FB65AF2720B59CCF',
    inputStream: fopen('myfile.jpg', 'rb'),
    outputStream: fopen('myfile-encrypted.bin', 'wb'),
);
```

```php
use Garsaud\CryptStreamAES\AES;
use Garsaud\CryptStreamAES\Length;

$aes = new AES(Length::AES256);

$aes->decrypt(
    key: 'B374A26A71490437AA024E4FADD5B497FDFF1A8EA6FF12F6FB65AF2720B59CCF',
    inputStream: fopen('myfile-encrypted.bin', 'rb'),
    outputStream: fopen('myfile.jpg', 'wb'),
);
```
