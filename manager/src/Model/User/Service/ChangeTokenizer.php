<?php

namespace App\Model\User\Service;

use Ramsey\Uuid\Uuid;

class ChangeTokenizer
{
    public function make(): string
    {
        return Uuid::uuid4()->toString();
    }
}