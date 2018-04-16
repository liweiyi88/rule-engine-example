<?php
declare(strict_types=1);

namespace App\BusinessObjects;

class User
{
    private $role;

    public function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role): void
    {
        $this->role = $role;
    }
}