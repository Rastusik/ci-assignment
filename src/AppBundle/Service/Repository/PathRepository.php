<?php

/**
 * @author     mfris
 */
namespace AppBundle\Service\Repository;

use AppBundle\Model\Entity\NodeEntity;
use AppBundle\Model\Entity\PathEntity;
use Doctrine\ORM\EntityRepository;

/**
 * @author mfris
 */
final class PathRepository extends EntityRepository
{

    /**
     *
     * @param NodeEntity $startNode
     * @param NodeEntity $endNode
     * @return PathEntity[]
     */
    public function findPaths(NodeEntity $startNode, NodeEntity $endNode)
    {
        $dql = "SELECT p FROM {$this->_entityName} AS p "
             . "WHERE p.startNode = :startNode AND p.endNode = :endNode";

        $paths = $this->_em->createQuery($dql)
            ->setParameter('startNode', $startNode)
            ->setParameter('endNode', $endNode)
            ->getResult();

        return $paths;
    }

    /**
     *
     * @param NodeEntity $startNode
     * @param NodeEntity $endNode
     * @return PathEntity|null
     */
    public function findCheapestPath(NodeEntity $startNode, NodeEntity $endNode)
    {
        $dql = "SELECT p FROM {$this->_entityName} AS p "
            . "WHERE p.startNode = :startNode AND p.endNode = :endNode";

        $path = $this->_em->createQuery($dql)
            ->setParameter('startNode', $startNode)
            ->setParameter('endNode', $endNode)
            ->setMaxResults(1)
            ->getOneOrNullResult();

        return $path;
    }
}
