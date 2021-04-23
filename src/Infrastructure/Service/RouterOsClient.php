<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use RouterOS\Client;
use RouterOS\Query;

final class RouterOsClient
{
    public function __construct(
        private Client $client,
    )
    {}

    public function getIdentity(): string
    {
        return $this->client->qr('/system/identity/print')[0]['name'];
    }

    public function getRouterBoard(): array
    {
        return $this->client->qr('/system/routerboard/print')[0];
    }

    public function getHealth(): array
    {
        return $this->client->qr('/system/health/print')[0];
    }

    public function getResources(): array
    {
        return $this->client->qr('/system/resource/print')[0];
    }

    public function getInterfaces(): array|string
    {
        return $this->client->qr('/interface/print');
    }

    public function getIpAddress(): array|string
    {
        return $this->client->qr('/ip/address/print');
    }

    public function getMonitoring(string $interfaceName): array|string
    {
        $query = new Query('/interface/monitor-traffic');
        $query
            ->equal('interface', $interfaceName)
            ->equal('once');

        return $this->client->qr($query)[0];
    }

    public function getArp(): array|string
    {
        return $this->client->qr('/ip/arp/print');
    }

    public function getDhcpLeases(): array|string
    {
        return $this->client->qr('/ip/dhcp-server/lease/print');
    }
}
