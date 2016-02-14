<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Dao;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Queries
 * @author mfris
 * @package AppBundle\Model\Dao
 */
final class Queries
{

    /**
     * @var ArrayCollection
     * @JMS\Type("ArrayCollection<AppBundle\Model\Dao\Query>")
     * @Assert\Count(min = "1")
     * @Assert\Valid()
     */
    private $queries;

    /**
     * Queries constructor.
     */
    public function __construct()
    {
        $this->queries = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getQueries()
    {
        return $this->queries;
    }
}
