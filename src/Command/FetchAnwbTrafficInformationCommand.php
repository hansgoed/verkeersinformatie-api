<?php

namespace App\Command;

use App\Anwb\TrafficInformationSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchAnwbTrafficInformationCommand extends Command
{
    protected static $defaultName = 'app:fetch-anwb-traffic-information';
    private TrafficInformationSynchronizer $trafficInformationSynchronizer;

    // the name of the command (the part after "bin/console")

    public function __construct(
        TrafficInformationSynchronizer $trafficInformationSynchronizer
    ) {
        parent::__construct();

        $this->trafficInformationSynchronizer = $trafficInformationSynchronizer;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Updates the database with the current information available from the ANWB.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->trafficInformationSynchronizer->synchronize();

        return 0;
    }
}