<?php

namespace Garsaud\CryptStreamAES;

enum Length: int
{
    case AES128 = 16;
    case AES192 = 24;
    case AES256 = 32;
}
