<?php
declare(strict_types=1);

namespace App\Domain;


final class User
{
    private int    $id    = 1;
    private string $login = 'admin';

    private function __construct()
    {
    }

    public static function create(): User
    {
        return new static();
    }

    public function id(): int
    {
        return $this->id;
    }
}
