<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Serializer;

use AppBundle\Model\Common\IdHavingInterface;
use JMS\Serializer\Context;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\GraphNavigator;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\XmlDeserializationVisitor;

/**
 *
 * @package Tsm\CommonBundle\Serializer
 */
class IdHavingHandler implements SubscribingHandlerInterface
{

    /**
     *
     * @var string
     */
    private $namespace;

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @param string        $namespace
     * @param EntityManager $em
     */
    public function __construct($namespace, EntityManager $em)
    {
        $this->setNamespace($namespace);
        $this->em = $em;
    }

    /**
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        $methods = [ ];
        $type = 'IdHaving';

        foreach ([ 'json', 'xml' ] as $format) {
            $methods[] = [
                'type'      => $type,
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format'    => $format,
            ];


            $methods[] = [
                'type'      => $type,
                'format'    => $format,
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'method'    => 'serializeIdEntity',
            ];
        }

        return $methods;
    }

    /**
     * serializer pre entity - vracia identifikator entity
     *
     * @param VisitorInterface  $visitor
     * @param IdHavingInterface $entity
     * @param array             $type
     * @param Context           $context
     *
     * @return int
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function serializeIdEntity(VisitorInterface $visitor, $entity, array $type, Context $context)
    {
        if ($entity instanceof IdHavingInterface) {
            return $entity->getId();
        }

        return null;
    }

    /**
     *
     * @param XmlDeserializationVisitor $visitor
     * @param mixed                       $data
     * @param array                       $type
     *
     * @return null
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function deserializeIdHavingFromXml(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        return $this->deserialize($data, $type);
    }

    /**
     * deserializuje json vstup
     *
     * @param JsonDeserializationVisitor $visitor
     * @param mixed                      $data
     * @param array                      $type
     *
     * @return null|IdHavingInterface
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function deserializeIdHavingFromJson(JsonDeserializationVisitor $visitor, $data, array $type)
    {
        return $this->deserialize($data, $type);
    }

    /**
     * nastavi namespace, ak na konci nema lomitko, prida ho
     *
     * @param string $namespace
     *
     * @return $this
     */
    private function setNamespace($namespace)
    {
        $namespace = trim((string)$namespace);

        if (strrpos($namespace, "\\") !== 0) {
            $namespace .= "\\";
        }

        $this->namespace = $namespace;

        return $this;
    }

    /**
     *
     * @param mixed $data
     * @param array $type
     *
     * @return null|IdHavingInterface
     * @throws \Exception
     */
    private function deserialize($data, array $type)
    {
        if (null === $data || "" === $data) {
            return null;
        }

        $params = $type['params'];
        $className = $this->namespace . $params[0];

        if (!$className) {
            throw new \Exception('Class name not provided.');
        }

        $repository = $this->em->getRepository($className);
        $entity = $repository->find($data);

        if (!$entity) {
            throw new \Exception("Entity not found.");
        }

        return $entity;
    }
}