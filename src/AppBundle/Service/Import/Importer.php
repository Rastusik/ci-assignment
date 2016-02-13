<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Import;

use AppBundle\Model\Entity\GraphEntity;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SplFileObject;
use Exception;
use UnexpectedValueException;

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
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Importer constructor.
     * @param Serializer $serializer
     * @param ValidatorInterface $validator
     * @param EntityManager $em
     */
    public function __construct(Serializer $serializer, ValidatorInterface $validator, EntityManager $em)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
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
            $graph = $this->serializer->deserialize($fileContent, GraphEntity::class, 'xml');
            $errors = $this->validator->validate($graph);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                throw new UnexpectedValueException($errorsString);
            }

            $this->em->persist($graph);
            $this->em->flush();
            $this->em->clear();
        } catch (Exception $e) {
            return new Result($e);
        }

        return new Result();
    }
}
