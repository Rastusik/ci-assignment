<?php
/**
 * @author  mfris
 */
namespace AppBundle\Service\Serializer\EventHandler;

use AppBundle\Model\Entity\GraphEntity;
use AppBundle\Model\Entity\NodeEntity;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

/**
 * Class PostSerializePersist
 * @author mfris
 * @package AppBundle\Service\Serializer\EventHandler
 */
final class PostDeserializeGraphAssign implements EventSubscriberInterface
{

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

        if ($object instanceof GraphEntity) {
            foreach ($object->getNodes() as $node) {
                /* @var $node NodeEntity */
                $node->setGraph($object);
            }
        }
    }
}
