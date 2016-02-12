<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

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
}
