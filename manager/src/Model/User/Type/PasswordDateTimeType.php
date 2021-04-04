<?php

namespace App\Model\User\Type;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Password\PasswordDateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

class PasswordDateTimeType extends StringType
{
    public const NAME = 'user_password_date_time';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof PasswordDateTime) {
            return $value->getValue()->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType(
            $value,
            $this->getName(),
            ['null', DateTimeImmutable::class]
        );
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PasswordDateTime
    {
        if ($value === null || $value instanceof PasswordDateTime) {
            return $value;
        }

        $dateTime = DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value);

        if (! $dateTime) {
            $dateTime = date_create_immutable($value);
        }

        if (! $dateTime) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return new PasswordDateTime($dateTime);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}