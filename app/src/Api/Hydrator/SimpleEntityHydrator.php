<?php

namespace KH\Api\Hydrator;

use Doctrine\Persistence\ManagerRegistry;
use KH\Entity\File;
use KH\Interfaces\EntityInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @package KH\Api\Hydrator
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class SimpleEntityHydrator
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ManagerRegistry $managerRegistry
    ) {}

    public function hydrate($data, $targetEntity, array $targetMap = [], array $defaultGroups = [], bool $fetchEntity = true)
    {
        $data = is_string($data) ? json_decode($data, 1) : $data;

        if (is_string($targetEntity)) {
            $targetEntity = $this->resolveTargetEntity($targetEntity, $data, $defaultGroups, $fetchEntity);
        }

        if (!empty($targetMap)) {
            $this->resolveMapEntities($data, $targetMap, $targetEntity, $defaultGroups);
        }

        return $targetEntity;
    }

    protected function resolveMapEntities(array &$data, array $targetMap, EntityInterface $originEntity, array $defaultGroups = [])
    {
        $groups = $defaultGroups;
        foreach($data as $field => $value) {
            $target = $targetMap[$field] ?? null;
            if ($target) {
                if (is_array($target)) {
                    $groups = array_merge($target['groups'] ?? [], $defaultGroups);

                    if (empty($target['class'])) {
                        throw new \Exception('Missing class definition');
                    }

                    $target = $target['class'];
                }

                if (is_array($value)) {
                    $temp[$field] = [];
                    foreach ($value as $item) {
                        $item = is_array($item) ? json_encode($item) : (string) $item;
                        $targetEntity = $this->hydrate($item, $target, defaultGroups: $groups);
                        $this->assign($field, $targetEntity, $originEntity);
                    }

                    continue;
                }

                $targetEntity = $this->hydrate(json_encode($data[$field]), $target, defaultGroups: $groups);
                $this->assign($field, $targetEntity, $originEntity);
            }
        }

        return $temp;
    }

    /**
     * @param string $targetEntity
     * @param $data
     * @return mixed|object|null
     */
    protected function resolveTargetEntity(string $targetEntity, $data, array $groups = [], bool $fetchEntity = true)
    {
        $id = is_numeric($data) ? $data : $data['id'] ?? null;
        if ($id && (is_numeric($data) || $fetchEntity)) {
            // this is id let's fetch entity
            $entity = $this->managerRegistry->getRepository($targetEntity)->find($id);
            if (!$entity) {
                return null;
            }

            // if we have data to update
            if (is_array($data)) {
                // lets update our entity
                $this->updateTargetEntityData($entity, $data);
            }

            return $entity;
        }

        $data = is_array($data) ? json_encode($data) : $data;
        return $this->serializer->deserialize($data, $targetEntity, JsonEncoder::FORMAT, $this->getGroups($groups));
    }

    protected function assign(string $fieldName, EntityInterface $mapEntity, EntityInterface $originEntity): void
    {
        $method = 'set' . ucfirst($fieldName);
        if (method_exists($originEntity, $method)) {
            $methodReflection = new \ReflectionMethod($originEntity, $method);
            $param = $methodReflection->getParameters()[0] ?? null;
            if ($param) {
                // let's pass arguments based on type
                $arguments = $param->getType()->getName() === 'array' ? [$mapEntity] : $mapEntity;
                // assign record to the origin entity
                call_user_func([$originEntity, $method], $arguments);
            }
        }

        $nameParts = explode('\\', get_class($originEntity));
        $method = 'set' . end($nameParts);
        // let's create association between entities
        if (method_exists($mapEntity, $method)) {
            $methodReflection = new \ReflectionMethod($mapEntity, $method);
            $param = $methodReflection->getParameters()[0] ?? null;
            if ($param) {
                // let's pass arguments based on type
                $arguments = $param->getType()->getName() === 'array' ? [$originEntity] : $originEntity;
                // assign record to the origin entity
                call_user_func([$mapEntity, $method], $arguments);
            }
        }
    }

    protected function getGroups(array $groups, array $defaultGroups = [])
    {
        if (empty($groups) && empty($defaultGroups)) {
            return [];
        }

        return ['groups' => array_merge($groups, $defaultGroups)];
    }

    protected function updateTargetEntityData(EntityInterface $entity, array $data)
    {
        foreach ($data as $field => $value) {
            if ($field !== 'id') {
                $method = 'set' . ucfirst($field);
                if (method_exists($entity, $method)) {
                    call_user_func([$entity, $method], $value);
                }
            }
        }
    }
}