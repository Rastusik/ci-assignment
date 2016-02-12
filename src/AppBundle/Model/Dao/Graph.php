<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Dao;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * Class GraphEntity
 * @author mfris
 * @package AppBundle\Model\Dao
 * @JMS\XmlRoot("graph")
 */
class Graph
{

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var string
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var ArrayCollection
     * @JMS\XmlList(entry="node")
     * @JMS\Type("ArrayCollection<AppBundle\Model\Entity\NodeEntity>")
     */
    private $nodes;

    /**
     * @var ArrayCollection
     * @JMS\XmlList(entry="node")
     * @JMS\Type("ArrayCollection<AppBundle\Model\Entity\EdgeEntity>")
     */
    private $edges;

    /**
     * GraphEntity constructor.
     */
    public function __construct()
    {
        $this->nodes = new ArrayCollection();
        $this->edges = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getNodes() : ArrayCollection
    {
        return $this->nodes;
    }
}
