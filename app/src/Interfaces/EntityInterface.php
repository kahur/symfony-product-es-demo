<?php

namespace KH\Interfaces;

/**
 * @package ${NAMESPACE}
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
interface EntityInterface
{
    public function getId():? int;
    public function setId(int $id): self;
    public function setCreatedAt(?\DateTimeInterface $dateTime): self;
    public function setUpdatedAt(?\DateTimeInterface $dateTime): self;
}