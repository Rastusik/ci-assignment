<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\PathFinding;

use AppBundle\Model\Entity\NodeEntity;
use AppBundle\Model\Entity\PathEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Class PathsAnswer
 * @author mfris
 * @package AppBundle\Service\PathFinding
 */
final class PathsAnswer extends Answer
{

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type = 'paths';

    /**
     * @var string[][]
     * @JMS\Type("array")
     */
    private $paths;

    /**
     * @var PathEntity[]
     * @JMS\Exclude()
     */
    private $pathEntities;

    /**
     * PathsAnswer constructor.
     * @param NodeEntity $from
     * @param NodeEntity $to
     * @param PathEntity[] $pathEntities
     */
    public function __construct(NodeEntity $from, NodeEntity $to, array $pathEntities)
    {
        parent::__construct($from, $to);
        $this->pathEntities = $pathEntities;
        $this->paths = $this->processPaths($pathEntities);
    }

    /**
     * @return NodeEntity[][]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param array $pathEntities
     * @return array
     */
    private function processPaths(array $pathEntities) : array
    {
        return array_reduce($pathEntities, function (array $result, PathEntity $path) {
            $result[] = $path->getPath()->getData();

            return $result;
        }, []);
    }
}
