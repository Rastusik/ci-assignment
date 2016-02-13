<?php

/*
 * Copyright 2013 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace AppBundle\Service\Serializer\Construction;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\UnitOfWork;
use JMS\Serializer\Construction\ObjectConstructorInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Metadata\ClassMetadata;
use JMS\Serializer\DeserializationContext;
use SimpleXMLElement;
use RuntimeException;

/**
 * Doctrine object constructor for new (or existing) objects during deserialization.
 */
class DoctrineObjectConstructor implements ObjectConstructorInterface
{

    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var ObjectConstructorInterface
     */
    private $fallbackConstructor;

    /**
     * @var array
     */
    private $newIds = [];

    /**
     * Constructor.
     *
     * @param ManagerRegistry            $managerRegistry     Manager registry
     * @param ObjectConstructorInterface $fallbackConstructor Fallback object constructor
     */
    public function __construct(ManagerRegistry $managerRegistry, ObjectConstructorInterface $fallbackConstructor)
    {
        $this->managerRegistry     = $managerRegistry;
        $this->fallbackConstructor = $fallbackConstructor;
    }

    /**
     * {@inheritdoc}
     * @throws RuntimeException
     */
    public function construct(
        VisitorInterface $visitor,
        ClassMetadata $metadata,
        $data,
        array $type,
        DeserializationContext $context
    ) {
        // Locate possible ObjectManager
        $objectManager = $this->managerRegistry->getManagerForClass($metadata->name);

        if (!$objectManager) {
            // No ObjectManager found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        // Locate possible ClassMetadata
        $classMetadataFactory = $objectManager->getMetadataFactory();

        if ($classMetadataFactory->isTransient($metadata->name)) {
            // No ClassMetadata found, proceed with normal deserialization
            return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
        }

        try {
            $id = $this->resolveIdentifier($metadata, $data, $objectManager);
        } catch (\Exception $e) {
            $id = null;
        }

        if ($id && ($entity = $objectManager->find($metadata->name, $id))) {
            /* @var $objectManager EntityManager */
            if ($this->isNewIdRegistered($metadata->name, $id)) {
                throw new RuntimeException("Not a unique entity '{$metadata->name}' with identifier '{$id}''");
            }

            $this->registerNewId($metadata->name, $id);

            return $entity;
        }

        // No ClassMetadata found, proceed with normal deserialization
        return $this->fallbackConstructor->construct($visitor, $metadata, $data, $type, $context);
    }

    /**
     * @param ClassMetadata $metadata
     * @param $data
     * @param ObjectManager $objectManager
     * @return string
     * @throws \Exception
     */
    private function resolveIdentifier(ClassMetadata $metadata, $data, ObjectManager $objectManager) : string
    {
        $id = '';

        // Managed entity, check for proxy load
        if (is_scalar($data)) {
            // Single identifier, load proxy
            $id = $data;
        }

        if (!$id && $data instanceof SimpleXMLElement) {
            // Fallback to default constructor if missing identifier(s)
            $classMetadata  = $objectManager->getClassMetadata($metadata->name);
            $idNames = $classMetadata->getIdentifierFieldNames();

            if (count($idNames) > 1) {
                throw new \Exception("Composed primary keys not supported");
            }

            $id = $data->{$idNames[0]}->__toString();
        }

        return $id;
    }

    /**
     * @param string $className
     * @param string $id
     * @return bool
     */
    private function isNewIdRegistered(string $className, string $id) : bool
    {
        return isset($this->newIds[$className]) && isset($this->newIds[$className][$id]);
    }

    /**
     * @param string $className
     * @param string $id
     */
    private function registerNewId(string $className, string $id)
    {
        if (!isset($this->newIds[$className])) {
            $this->newIds[$className] = [];
        }

        $this->newIds[$className][$id] = $id;
    }
}
