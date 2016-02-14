<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 11.9.2015
 * Time: 16:33
 */

namespace AppBundle\Command;

use AppBundle\Model\Dao\Queries;
use AppBundle\Service\PathFinding\PathFinder;
use JMS\Serializer\Serializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @package AppBundle\Command
 */
final class QueryCommand extends Command
{

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var PathFinder
     */
    private $pathFinder;

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param PathFinder $pathFinder
     */
    public function setPathFinder(PathFinder $pathFinder)
    {
        $this->pathFinder = $pathFinder;
    }

    /**
     * Basic command configuration
     */
    protected function configure()
    {
        $this->setName('ci_test:importer:query');
        $this->setDescription('Query graph data.');
    }

    /**
     * Runs the command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $content = '';

        while (($line = fgets(STDIN)) !== false) {
            $content .= $line;
        }

        if (!$content) {
            return false;
        }

        $queries = $this->serializer->deserialize($content, Queries::class, 'json');
        $result = $this->pathFinder->findPaths($queries);

        if ($result->isSuccess()) {
            $json = $this->serializer->serialize($result, 'json');
            $output->write($json);

            return true;
        }

        $output->writeln(
            'Error while processing query:'
            . PHP_EOL . PHP_EOL
            . $result->getException()->getMessage()
            . PHP_EOL . PHP_EOL
            . $result->getException()->getFile() . ":" . $result->getException()->getLine()
        );

        return false;
    }
}
