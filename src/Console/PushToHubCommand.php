<?php

declare(strict_types=1);

namespace App\Console;

use App\Model\DataCollectorInterface;
use App\Network\DataSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class PushToHubCommand extends Command
{
    public function __construct(
        private readonly DataSender $dataSender,

        #[AutowireIterator(tag: 'data_collector')]
        private readonly iterable $collectors,
    ) {
        parent::__construct('push-to-hub');
    }

    protected function configure(): void
    {
        $this->addArgument('project', InputArgument::REQUIRED, 'The name of the project');
        $this->addArgument('environment', InputArgument::REQUIRED, 'The name of the deployment environment');
        $this->addArgument('dir', InputArgument::OPTIONAL, 'Project directory to read data from', default: (string) getcwd());
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $directory */
        $directory = $input->getArgument('dir');

        $result = [];

        /** @var DataCollectorInterface $collector */
        foreach ($this->collectors as $collector) {
            $collectorResult = $collector->collect($directory);

            if (null === $collectorResult) {
                continue;
            }

            $result = [...$result, ...$collectorResult];
        }

        $data = ['project' => $input->getArgument('project'), 'environment' => $input->getArgument('environment'), ...$result];

        if ($output->isVerbose()) {
            $output->writeln('<info>Pushing to Hub</info>');
            $output->writeln((string) json_encode($data, JSON_PRETTY_PRINT));
        }

        $this->dataSender->send($data);

        return Command::SUCCESS;
    }
}
