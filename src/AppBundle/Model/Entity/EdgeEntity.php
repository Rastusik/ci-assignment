<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class EdgeEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Entity
 * @ORM\Table(name="edge", options={"comment":"A one to one mapping to the XML graph edge"})
 * @UniqueEntity("id")
 */
class EdgeEntity extends AbstractEntity
{

    /**
     * @var GraphEntity
     * @ORM\ManyToOne(targetEntity="AppBundle\Model\Entity\GraphEntity", inversedBy="edges")
     * @ORM\JoinColumn(name="graph_id", referencedColumnName="id", nullable=false)
     * @Assert\NotNull()
     */
    private $graph;

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity", inversedBy="edgesFrom")
     * @ORM\JoinColumn(
     *     name="from_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     * @JMS\Type("IdHaving<'NodeEntity'>")
     * @Assert\NotNull()
     */
    private $from;

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity", inversedBy="edgesTo")
     * @ORM\JoinColumn(
     *     name="to_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     * @JMS\Type("IdHaving<'NodeEntity'>")
     * @Assert\NotNull()
     */
    private $to;

    /**
     * @var float
     * @ORM\Column(
     *     type="decimal",
     *     precision=7,
     *     scale=2,
     *     options={
     *      "default"=0,
     *      "unsigned"=true,
     *      "comment":"Unsigned value is checked during validation."
     *     }
     * )
     * @JMS\Type("double")
     * @Assert\GreaterThanOrEqual(value=0)
     * @Assert\Type(type="numeric")
     */
    private $cost = 0;

    /**
     * @return GraphEntity
     */
    public function getGraph() : GraphEntity
    {
        return $this->graph;
    }

    /**
     * @param GraphEntity $graph
     * @return EdgeEntity
     */
    public function setGraph(GraphEntity $graph) : EdgeEntity
    {
        $this->graph = $graph;

        return $this;
    }

    /**
     * @return NodeEntity
     */
    public function getFrom() : NodeEntity
    {
        return $this->from;
    }

    /**
     * @return NodeEntity
     */
    public function getTo() : NodeEntity
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function getCost() : float
    {
        return $this->cost;
    }
}
