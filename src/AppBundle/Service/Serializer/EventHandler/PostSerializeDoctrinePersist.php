<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Serializer\EventHandler;

use AppBundle\Model\Entity\AbstractEntity;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

/**
 * Class PostSerializePersist
 * @author mfris
 * @package AppBundle\Service\Serializer\EventHandler
 */
final class PostSerializeDoctrinePersist implements EventSubscriberInterface
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * PostSerializePersist constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_deserialize',
                'method' => 'onPostDeserialize',
            ],
        ];
    }

    /**
     * @param ObjectEvent $event
     * @return void
     */
    public function onPostDeserialize(ObjectEvent $event)
    {
        $object = $event->getObject();

        if (!$object instanceof AbstractEntity) {
            return;
        }

        $this->em->persist($object);
    }
}
