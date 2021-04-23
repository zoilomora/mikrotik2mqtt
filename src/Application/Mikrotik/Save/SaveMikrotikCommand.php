<?php
declare(strict_types=1);

namespace App\Application\Mikrotik\Save;

final class SaveMikrotikCommand
{
    public function __construct(
        private array $data,
    )
    {}

    public function data(): array
    {
        return $this->data;
    }
}
