<?php
declare(strict_types=1);

namespace App\Domain\Model\Mikrotik;

interface MikrotikRepository
{
    public function get(): array;
    public function save(array $mikrotik): void;
}
