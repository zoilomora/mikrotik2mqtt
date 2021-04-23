<?php
declare(strict_types=1);

namespace App\Application\Mikrotik\Get;

use App\Domain\Model\Mikrotik\MikrotikRepository;

final class GetMikrotikCommandHandler
{
    public function __construct(
        private MikrotikRepository $readRepository,
    )
    {}

    public function execute(GetMikrotikCommand $command): array
    {
        return $this->readRepository->get();
    }
}
