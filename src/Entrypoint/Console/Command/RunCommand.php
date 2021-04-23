<?php
declare(strict_types=1);

namespace App\Entrypoint\Console\Command;

use App\Application\Mikrotik\Get\GetMikrotikCommand;
use App\Application\Mikrotik\Get\GetMikrotikCommandHandler;
use App\Application\Mikrotik\Save\SaveMikrotikCommand;
use App\Application\Mikrotik\Save\SaveMikrotikCommandHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RunCommand extends Command
{
    protected static $defaultName = 'app:run';

    public function __construct(
        private int $updateTime,
        private GetMikrotikCommandHandler $getMikrotikCommandHandler,
        private SaveMikrotikCommandHandler $saveMikrotikCommandHandler,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Run Application!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('MikroTik to MQTT initialized.' . PHP_EOL);

        while (true) {
            $output->writeln('Getting data from MikroTik API (RouterOS)...');
            $mikrotik = $this->getMikrotikCommandHandler->execute(
                new GetMikrotikCommand()
            );

            $output->writeln('Publishing the data to MQTT...');
            $this->saveMikrotikCommandHandler->execute(
                new SaveMikrotikCommand($mikrotik)
            );

            $output->writeln('Process completed successfully!' . PHP_EOL);
            $output->writeln(
                \sprintf(
                    'Waiting %d seconds for the next iteration...%s',
                    $this->updateTime,
                    PHP_EOL,
                ),
            );
            \sleep($this->updateTime);
        }
    }
}
