<?php

namespace KH\Service;


use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use KH\Interfaces\EntityInterface;

/**
 * @package KH\Service
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class CrudService
{
    public function __construct(
        protected ManagerRegistry $managerRegistry
    ) {}

    /**
     * @template T of object
     * @param object<T> $entity
     * @return T|EntityInterface
     */
    public function save(EntityInterface $entity)
    {
        if ($entity->getId()) {
            $entity->setUpdatedAt(new \DateTime());
        } else {
            $entity->setCreatedAt(new \DateTime());
        }

        $this->managerRegistry->getManager()->persist($entity);
        $this->managerRegistry->getManager()->flush();

        return $entity;
    }

    public function delete(EntityInterface $entity)
    {
        $this->managerRegistry->getManager()->remove($entity);
        $this->managerRegistry->getManager()->flush();
    }

    /**
     * @template T of object
     * @param class-string<T> $entityName
     *
     * @return T[]
     */
    public function findAll(string $entityName)
    {
        return $this->getRepository($entityName)->findAll();
    }

    /**
     * @template T of object
     * @param class-string<T> $entityName
     * @param $id
     * @return T|null
     */
    public function findOne(string $entityName, $id)
    {
        return $this->getRepository($entityName)->find($id);
    }

    /**
     * @template T[] of object[]
     * @param class-string<T> $entityName
     * @param array $filter
     * @return T[]|array
     */
    public function findBy(string $entityName, array $filter = [])
    {
        return $this->getRepository($entityName)->findBy($filter);
    }

    /**
     * @param EntityInterface|string $entity
     * @return ObjectRepository
     */
    public function getRepository($entity): ObjectRepository
    {
        if ($entity instanceof EntityInterface) {
            $entity = get_class($entity);
        }

        return $this->managerRegistry->getRepository($entity);
    }
}