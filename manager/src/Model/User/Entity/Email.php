<?php

namespace App\Model\User\Entity;

class Email
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Incorrect email value given.');
        }

        $this->value = mb_strtolower($value);
    }

    public function getValue()
    {
        return $this->value;
    }
}