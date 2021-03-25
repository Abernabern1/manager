<?php

namespace App\Model\User\Type;

use App\Model\User\Entity\ConfirmToken;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ConfirmTokenType extends StringType
{
    public const NAME = 'user_confirm_token';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof ConfirmToken ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new ConfirmToken($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}