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
final class CheapestAnswer extends Answer
{

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $type = 'cheapest';

    /**
     * @var string[]|bool
     */
    private $path;

    /**
     * @var PathEntity
     * @JMS\Exclude()
     */
    private $pathEntity;

    /**
     * PathsAnswer constructor.
     * @param NodeEntity $from
     * @param NodeEntity $to
     * @param PathEntity $pathEntity
     */
    public function __construct(NodeEntity $from, NodeEntity $to, PathEntity $pathEntity = null)
    {
        parent::__construct($from, $to);
        $this->pathEntity = $pathEntity;
        $this->path = $pathEntity ? $pathEntity->getPath()->getData() : false;
    }

    /**
     * @return string[]|bool
     */
    public function getPath()
    {
        return $this->path;
    }
}
