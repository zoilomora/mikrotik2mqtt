<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use RouterOS\Client;
use RouterOS\Config;

final class RouterOsClientFactory
{
    public function __construct(
        private string $host,
        private bool $useSsl,
        private string $user,
        private string $password,
    )
    {}

    public function build(): RouterOsClient
    {
        $config = new Config();
        $config
            ->set('host', $this->host)
            ->set('ssl', $this->useSsl)
            ->set('user', $this->user)
            ->set('pass', $this->password);

        return new RouterOsClient(
            new Client($config),
        );
    }
}
