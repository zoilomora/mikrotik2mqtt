<?php
declare(strict_types=1);

namespace App\Infrastructure\Domain\Model\Mikrotik;

use App\Domain\Model\Mikrotik\MikrotikRepository;
use App\Infrastructure\Service\MqttClientFactory;
use PhpMqtt\Client\MqttClient;

final class MqttMikrotikRepository implements MikrotikRepository
{
    private ?MqttClient $client = null;

    public function __construct(
        private MqttClientFactory $clientFactory,
        private string $mqttTopicBase,
    )
    {}

    public function get(): array
    {
        throw new \Exception('This method is not implemented.');
    }

    public function save(array $mikrotik): void
    {
        $this->buildClient();

        $this->client->connect();

        $this->client->publish(
            $this->topicGenerate($mikrotik),
            \json_encode($mikrotik),
            0
        );

        $this->client->disconnect();
    }

    private function buildClient(): void
    {
        if (null === $this->client) {
            $this->client = $this->clientFactory->build();
        }
    }

    private function topicGenerate(array $mikrotik): string
    {
        return $this->mqttTopicBase . '/' . $mikrotik['routerBoard']['serialNumber'];
    }
}
