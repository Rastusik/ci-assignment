<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\PathFinding;

use AppBundle\Model\Dao\Queries;
use AppBundle\Model\Dao\Query;
use AppBundle\Service\Repository\PathRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use UnexpectedValueException;
use Exception;

/**
 * Class PathFinder
 * @author mfris
 * @package AppBundle\Service\PathFinding
 */
final class PathFinder
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var PathRepository
     */
    private $pathRepository;

    /**
     * PathFinder constructor.
     * @param ValidatorInterface $validator
     * @param PathRepository $pathRepository
     */
    public function __construct(ValidatorInterface $validator, PathRepository $pathRepository)
    {
        $this->validator = $validator;
        $this->pathRepository = $pathRepository;
    }

    /**
     * @param Queries $queries
     * @return Result
     */
    public function findPaths(Queries $queries) : Result
    {
        try {
            $this->validateQueryies($queries);
            $answers = $this->evaluateQueries($queries);

            return new Result($answers);
        } catch (Exception $e) {
            return new Result([], $e);
        }

    }

    /**
     * @param Queries $queries
     * @return void
     * @throws UnexpectedValueException
     */
    private function validateQueryies(Queries $queries)
    {
        $errors = $this->validator->validate($queries);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new UnexpectedValueException($errorsString);
        }
    }

    /**
     * @param Queries $queries
     * @return array
     */
    private function evaluateQueries(Queries $queries) : array
    {
        $answers = [];

        /* @var $query Query */
        foreach ($queries->getQueries() as $query) {
            $startNode = $query->getStartNode();
            $endNode = $query->getEndNode();

            switch ($query->getType()) {
                case Query::TYPE_PATHS:
                    $paths = $this->pathRepository->findPaths($startNode, $endNode);
                    $answers[] = new PathsAnswer($startNode, $endNode, $paths);
                    break;
                case Query::TYPE_CHEAPEST:
                    $path = $this->pathRepository->findCheapestPath($startNode, $endNode);
                    $answers[] = new CheapestAnswer($startNode, $endNode, $path);
            }
        }

        return $answers;
    }
}
