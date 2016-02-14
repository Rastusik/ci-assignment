<?php
/**
 * @author  mfris
 */
namespace AppBundle\Model\Entity;

use AppBundle\Model\DBAL\Type\StringArray;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class PathEntity
 * @author mfris
 * @package AppBundle\Model\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Service\Repository\PathRepository")
 * @ORM\Table(name="path")
 */
class PathEntity extends AbstractEntity
{

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity")
     * @ORM\JoinColumn(
     *     name="start_node_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     */
    private $startNode;

    /**
     * @var NodeEntity
     * @ORM\ManyToOne(targetEntity="NodeEntity")
     * @ORM\JoinColumn(
     *     name="end_node_id",
     *     referencedColumnName="id",
     *     nullable=false
     * )
     */
    private $endNode;

    /**
     * @var StringArray
     * @ORM\Column(type="StringArray", nullable=false)
     */
    private $path;

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

    /**
     * @return StringArray
     */
    public function getPath()
    {
        return $this->path;
    }
}
