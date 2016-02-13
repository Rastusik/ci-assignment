<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Import;

use AppBundle\Model\Entity\GraphEntity;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use SplFileObject;

/**
 * Class Importer
 * @author mfris
 * @package AppBundle\Service
 */
final class Importer
{

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Importer constructor.
     * @param Serializer $serializer
     * @param EntityManager $em
     */
    public function __construct(Serializer $serializer, EntityManager $em)
    {
        $this->serializer = $serializer;
        $this->em = $em;
    }

    /**
     * @param SplFileObject $file
     * @return Result
     */
    public function import(SplFileObject $file) : Result
    {
        try {
            $fileContent = $file->fread($file->getSize());

            /* @var $graph GraphEntity */
            $this->serializer->deserialize($fileContent, GraphEntity::class, 'xml');
            $this->em->flush();
            $this->em->clear();
        } catch (\Exception $e) {
            return new Result($e);
        }

        return new Result();
    }
}
