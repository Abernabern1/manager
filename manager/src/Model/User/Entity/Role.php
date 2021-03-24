<?php

namespace App\Model\User\Entity;

class Role
{
    public const USER = 'ROLE_USER';

    private $name;

    public function __construct($name)
    {
        if(!$this->roleExists($name)) {
            throw new \InvalidArgumentException('Given role not found.');
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function roleExists(String $role): bool
    {
        if(in_array($role, static::getRoleList())) {
            return true;
        }

        return false;
    }

    public static function getRoleList(): array
    {
        return [
            self::USER,
        ];
    }
}