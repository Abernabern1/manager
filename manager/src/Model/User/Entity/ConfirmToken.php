<?php

namespace App\Model\User\Entity;

class ConfirmToken
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function valueEquals(string $value): bool
    {
        if($this->value === $value) {
            return true;
        }

        return false;
    }
}
