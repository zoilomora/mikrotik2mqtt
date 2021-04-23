<?php
declare(strict_types=1);

namespace App\Application\Mikrotik\Save;

use App\Domain\Model\Mikrotik\MikrotikRepository;

final class SaveMikrotikCommandHandler
{
    public function __construct(
        private MikrotikRepository $writeRepository,
    )
    {}

    public function execute(SaveMikrotikCommand $command): void
    {
        $this->writeRepository->save(
            $command->data()
        );
    }
}
