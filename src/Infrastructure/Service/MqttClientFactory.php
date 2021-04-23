<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use PhpMqtt\Client\ConnectionSettings;

final class MqttClientFactory
{
    public function __construct(
        private string $host,
        private int $port,
        private string $clientId,
        private string $username,
        private string $password,
        private bool $useTls,
    )
    {}

    public function build(): MqttClient
    {
        $client = new MqttClient(
            $this->host,
            $this->port,
            $this->clientId,
        );

        $settings = new ConnectionSettings();
        $settings = $settings->setUseTls($this->useTls);

        if ('' !== $this->username && '' !== $this->password) {
            $settings = $settings
                ->setUsername($this->username)
                ->setPassword($this->password);
        }

        $client->connect($settings);
        $client->disconnect();

        return $client;
    }
}
