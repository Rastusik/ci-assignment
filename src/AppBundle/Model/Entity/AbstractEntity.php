<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AbstractEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 */
abstract class AbstractEntity
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="string")
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
