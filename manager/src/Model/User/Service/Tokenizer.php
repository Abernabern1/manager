<?php

namespace App\Model\User\Service;

use Ramsey\Uuid\Uuid;

class Tokenizer
{
    public function make()
    {
        return Uuid::uuid4()->toString();
    }
}