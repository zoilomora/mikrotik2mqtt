<?php
declare(strict_types=1);

namespace App\Infrastructure\Service;

use PhpMqtt\Client\ConnectionSettings;

class MqttClient extends \PhpMqtt\Client\MqttClient
{
    private ConnectionSettings $settingsSaved;

    public function connect(ConnectionSettings $settings = null, bool $useCleanSession = false): void
    {
        if (null !== $settings) {
            $this->settingsSaved = $settings;
        }

        parent::connect($this->settingsSaved, $useCleanSession);
    }
}
