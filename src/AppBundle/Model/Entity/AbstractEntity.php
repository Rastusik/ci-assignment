<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use AppBundle\Model\Common\IdHavingInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 */
abstract class AbstractEntity implements IdHavingInterface
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string", options={"comment":"A string identifier, imported from the XML file"})
     * @JMS\Type("string")
     * @Assert\NotBlank()
     */
    protected $id = '';

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }
}
