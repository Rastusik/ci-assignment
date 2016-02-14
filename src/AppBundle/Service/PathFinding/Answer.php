<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\PathFinding;

use AppBundle\Model\Entity\NodeEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Answer
 * @author mfris
 * @package AppBundle\Service\PathFinding
 */
abstract class Answer
{

    /**
     * @var NodeEntity
     * @JMS\Type("IdHaving<'NodeEntity'>")
     */
    protected $from;

    /**
     * @var NodeEntity
     * @JMS\Type("IdHaving<'NodeEntity'>")
     */
    protected $to;

    /**
     * Answer constructor.
     * @param NodeEntity $from
     * @param NodeEntity $to
     */
    public function __construct(NodeEntity $from, NodeEntity $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return NodeEntity
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return NodeEntity
     */
    public function getTo()
    {
        return $this->to;
    }
}
