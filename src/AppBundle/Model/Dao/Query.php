<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Dao;

use AppBundle\Model\Entity\NodeEntity;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Query
 * @author mfris
 * @package AppBundle\Model\Dao
 */
final class Query
{

    const TYPE_PATHS = 'paths';
    const TYPE_CHEAPEST = 'cheapest';

    /**
     * @var string
     * @JMS\Type("string")
     * @Assert\Choice(
     *     choices = { "paths", "cheapest" },
     *     message = "Invalid query type."
     * )
     */
    private $type;

    /**
     * @var NodeEntity
     * @JMS\Type("IdHaving<'NodeEntity'>")
     * @JMS\SerializedName("start")
     * @Assert\NotNull()
     */
    private $startNode;

    /**
     * @var NodeEntity
     * @JMS\Type("IdHaving<'NodeEntity'>")
     * @JMS\SerializedName("end")
     * @Assert\NotNull()
     */
    private $endNode;

    /**
     * Query constructor.
     * @param string $type
     * @param NodeEntity $startNode
     * @param NodeEntity $endNode
     */
    public function __construct($type, NodeEntity $startNode, NodeEntity $endNode)
    {
        $this->type = $type;
        $this->startNode = $startNode;
        $this->endNode = $endNode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return NodeEntity
     */
    public function getStartNode()
    {
        return $this->startNode;
    }

    /**
     * @return NodeEntity
     */
    public function getEndNode()
    {
        return $this->endNode;
    }
}
