<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\ConfirmToken;
use Ramsey\Uuid\Uuid;

class ConfirmTokenizer
{
    public function make()
    {
        return new ConfirmToken(Uuid::uuid4()->toString());
    }
}