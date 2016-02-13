<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\ExpressionLanguage\Node\Node;

/**
 * Class NodeEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Entity
 * @ORM\Table(name="node")
 */
class NodeEntity extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string");
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var GraphEntity
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\GraphEntity", inversedBy="nodes")
     * @ORM\JoinColumn(name="graph_id", referencedColumnName="id", nullable=false)
     */
    private $graph;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="EdgeEntity",
     *     mappedBy="from",
     *     cascade={"persist","detach"}
     * )
     */
    private $edgesFrom;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="EdgeEntity",
     *     mappedBy="to",
     *     cascade={"persist","detach"}
     * )
     */
    private $edgesTo;

    /**
     * NodeEntity constructor.
     */
    public function __construct()
    {
        $this->edgesFrom = new ArrayCollection();
        $this->edgesTo = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @return GraphEntity
     */
    public function getGraph() : GraphEntity
    {
        return $this->graph;
    }

    /**
     * @param GraphEntity $graph
     * @return NodeEntity
     */
    public function setGraph(GraphEntity $graph) : NodeEntity
    {
        $this->graph = $graph;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEdgesFrom() : ArrayCollection
    {
        return $this->edgesFrom;
    }

    /**
     * @return ArrayCollection
     */
    public function getEdgesTo() : ArrayCollection
    {
        return $this->edgesTo;
    }
}
