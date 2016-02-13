<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class GraphEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Entity
 * @ORM\Table(name="graph")
 * @JMS\XmlRoot("graph")
 */
class GraphEntity extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string")
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(
     *     targetEntity="AppBundle\Model\Entity\NodeEntity",
     *     mappedBy="graph",
     *     cascade={"persist","detach"}
     * )
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
     * @return ArrayCollection
     */
    public function getNodes() : ArrayCollection
    {
        return $this->nodes;
    }

    /**
     * @return ArrayCollection
     */
    public function getEdges() : ArrayCollection
    {
        return $this->edges;
    }
}
