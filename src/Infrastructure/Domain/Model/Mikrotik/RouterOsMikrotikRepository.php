<?php
declare(strict_types=1);

namespace App\Infrastructure\Domain\Model\Mikrotik;

use App\Domain\Model\Mikrotik\MikrotikRepository;
use App\Infrastructure\Service\FormatMikrotikDuration;
use App\Infrastructure\Service\RouterOsClient;
use App\Infrastructure\Service\RouterOsClientFactory;
use JetBrains\PhpStorm\ArrayShape;

final class RouterOsMikrotikRepository implements MikrotikRepository
{
    private ?RouterOsClient $client = null;

    public function __construct(
        private RouterOsClientFactory $clientFactory,
    )
    {}

    #[ArrayShape(['identity' => "string", 'routerBoard' => "array", 'resources' => "array", 'health' => "array", 'interfaces' => "array", 'devices' => "array"])]
    public function get(): array
    {
        $this->buildClient();

        return [
            'identity' => $this->getIdentity(),
            'routerBoard' => $this->getRouterBoard(),
            'resources' => $this->getResources(),
            'health' => $this->getHealth(),
            'interfaces' => $this->getInterfaces(),
            'devices' => $this->getDevices(),
        ];
    }

    public function save(array $mikrotik): void
    {
        throw new \Exception('This method is not implemented.');
    }

    private function buildClient(): void
    {
        if (null === $this->client) {
            $this->client = $this->clientFactory->build();
        }
    }

    private function getIdentity(): string
    {
        return $this->client->getIdentity();
    }

    #[ArrayShape(['boardName' => "string", 'model' => "string", 'serialNumber' => "string", 'firmware' => "string[]"])]
    private function getRouterBoard(): array
    {
        $data = $this->client->getRouterBoard();

        return [
            'boardName' => (string) $data['board-name'],
            'model' => (string) $data['model'],
            'serialNumber' => (string) $data['serial-number'],
            'firmware' => [
                'factory' => (string) $data['factory-firmware'],
                'current' => (string) $data['current-firmware'],
                'upgrade' => (string) $data['upgrade-firmware'],
            ],
        ];
    }

    #[ArrayShape(["upTime" => "int", "architecture" => "string", "cpu" => "array", "memory" => "int[]", "hdd" => "int[]"])]
    private function getResources(): array
    {
        $data = $this->client->getResources();

        return [
            "upTime" => FormatMikrotikDuration::fromStringToInt($data['uptime']),
            "architecture" => \strtoupper($data['architecture-name']),
            "cpu" => [
                "model" => (string) $data['cpu'],
                "numCores" => (int) $data['cpu-count'],
                "frequencyMhz" => (int) $data['cpu-frequency'],
                "load" => (float) $data['cpu-load'],
            ],
            "memory" => [
                "free" => (int) $data['free-memory'],
                "total" => (int) $data['total-memory'],
            ],
            "hdd" => [
                "free" => (int) $data['free-hdd-space'],
                "total" => (int) $data['total-hdd-space'],
            ]
        ];
    }

    #[ArrayShape(['voltage' => "float", 'temperature' => "int"])]
    private function getHealth(): array
    {
        $data = $this->client->getHealth();

        return [
            'voltage' => floatval($data['voltage']),
            'temperature' => intval($data['temperature']),
        ];
    }

    private function getInterfaces(): array
    {
        $ipAddress = $this->client->getIpAddress();

        $ipAddressOrdered = [];
        foreach ($ipAddress as $ip) {
            if (false === \array_key_exists($ip['interface'], $ipAddressOrdered)) {
                $ipAddressOrdered[$ip['interface']] = [];
            }

            $address = \explode('/', $ip['address']);

            $ipAddressOrdered[$ip['interface']][] = $address[0];
        }

        $interfaces = $this->client->getInterfaces();

        $result = [];
        foreach ($interfaces as $interface) {
            $name = (string) $interface['name'];

            $address = \array_key_exists($name, $ipAddressOrdered)
                ? $ipAddressOrdered[$name]
                : [];

            $monitoring = $this->client->getMonitoring($name);

            $result[] = [
                'name' => $name,
                'comment' => \array_key_exists('comment', $interface)
                    ? \utf8_encode($interface['comment'])
                    : '',
                'type' => (string) $interface['type'],
                'lastLinkUpTime' => \array_key_exists('last-link-up-time', $interface)
                    ? $this->formatLastLinkUpTime($interface['last-link-up-time'])
                    : '',
                'address' => [
                    'mac' => (string) $interface['mac-address'],
                    'ip' => $address,
                ],
                'running' => 'true' === $interface['running'],
                'disabled' => 'true' === $interface['disabled'],
                'traffic' => [
                    'rxBitsPerSecond' => (int) $monitoring['rx-bits-per-second'],
                    "txBitsPerSecond" => (int) $monitoring['tx-bits-per-second'],
                ],
            ];
        }

        return $result;
    }

    private function formatLastLinkUpTime($lastLinkUpTime): string
    {
        $datetime = \DateTime::createFromFormat('M/d/Y H:i:s', $lastLinkUpTime);

        return $datetime->format('Y-m-d H:i:s');
    }

    private function getDevices(): array
    {
        $leases = $this->client->getDhcpLeases();

        $leasesOrdered = [];
        foreach ($leases as $lease) {
            $leasesOrdered[$lease['mac-address']] = [
                'comment' => \array_key_exists('comment', $lease)
                    ? \utf8_encode($lease['comment'])
                    : '',
            ];
        }

        $arps = $this->client->getArp();

        $result = [];
        foreach ($arps as $arp) {
            if (false === \array_key_exists('mac-address', $arp)) {
                continue;
            }

            $ipAddress = $arp['address'];
            $macAddress = $arp['mac-address'];

            $lease = \array_key_exists($macAddress, $leasesOrdered)
                ? $leasesOrdered[$macAddress]
                : ['comment' => ''];

            $result[] = [
                'comment' => (string) $lease['comment'],
                'address' => [
                    'ip' => (string) $ipAddress,
                    'mac' => (string) $macAddress,
                ],
            ];
        }

        return $result;
    }
}
