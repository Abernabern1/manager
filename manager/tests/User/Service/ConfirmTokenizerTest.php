<?php

namespace App\Tests\User\Service;

use App\Model\User\Entity\ConfirmToken;
use App\Model\User\Service\ConfirmTokenizer;
use PHPUnit\Framework\TestCase;

class ConfirmTokenizerTest extends TestCase
{
    public function testSuccess(): void
    {
        $confirmTokenizer = new ConfirmTokenizer();

        $confirmToken = $confirmTokenizer->make();

        $this->assertTrue($confirmToken instanceof ConfirmToken);
    }
}
