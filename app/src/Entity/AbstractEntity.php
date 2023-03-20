<?php

namespace KH\Entity;


use KH\Interfaces\EntityInterface;

/**
 * @package KH\Entity
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
abstract class AbstractEntity implements EntityInterface
{
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}