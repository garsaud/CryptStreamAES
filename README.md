# CryptStreamAES

[![GitHub CI](https://github.com/garsaud/CryptStreamAES/actions/workflows/php.yml/badge.svg)](https://github.com/garsaud/CryptStreamAES/actions)

This package encrypts and decrypts resources (streams) using AES 128, 192 or 256 using mcrypt.

It operates on resources (local or remote obtained via `fopen(…)`) in chunks, making it possible to process very large contents without exceeding the memory limit.
