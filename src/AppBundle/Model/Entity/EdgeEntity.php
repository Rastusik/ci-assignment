<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class EdgeEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Entity
 * @ORM\Table(name="edge")
 */
class EdgeEntity extends AbstractEntity
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
     * @JMS\Type("string")
     */
    private $id;

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity", inversedBy="edgesFrom")
     * @ORM\JoinColumn(name="from_id", referencedColumnName="id")
     * @JMS\Type("IdHaving<'NodeEntity'>")
     */
    private $from;

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity", inversedBy="edgesTo")
     * @ORM\JoinColumn(name="to_id", referencedColumnName="id")
     * @JMS\Type("IdHaving<'NodeEntity'>")
     */
    private $to;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=7, scale=2, options={"default" = 0})
     * @JMS\Type("double")
     */
    private $cost = 0;
}
