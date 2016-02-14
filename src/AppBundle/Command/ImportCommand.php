<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.9.2015
 * Time: 16:33
 */

namespace AppBundle\Command;

use AppBundle\Service\DownloadingImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @package AppBundle\Command
 */
final class ImportCommand extends Command
{

    /**
     * @var DownloadingImporter
     */
    private $importer;

    /**
     * @param DownloadingImporter $importer
     */
    public function setImporter(DownloadingImporter $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Basic command configuration
     */
    protected function configure()
    {
        $this->setName('ci_test:importer:import');
        $this->setDescription('Download and import graph data.');
        $this->setDefinition(
            new InputDefinition([
                new InputOption('download', 'd', InputOption::VALUE_OPTIONAL, 'Graph file url', ''),
            ])
        );
    }

    /**
     * Runs the command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Processing ...");
        $graphFileUrl = $input->getOption('download');
        $result = $this->importer->downloadAndImport($graphFileUrl);

        if ($result->isSuccess()) {
            $output->writeln("Import complete.");

            return true;
        }

        $output->writeln(
            'Error importing graph data:'
            . PHP_EOL . PHP_EOL
            . $result->getException()->getMessage()
            . PHP_EOL . PHP_EOL
            . $result->getException()->getFile() . ":" . $result->getException()->getLine()
        );

        return false;
    }
}
