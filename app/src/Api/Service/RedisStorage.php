<?php

namespace KH\Api\Service;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @package KH\Api\Service
 * @author Kamil Hurajt <hurajtk@gmail.com>
 */
class RedisStorage
{
    public function __construct(
        protected RedisTagAwareAdapter $redis,
        protected SerializerInterface $serializer
    ) {}

    public function save($name, $value, array $tags = ['default'], array $serializeGroups = []): bool
    {
        $groups = empty($serializeGroups) ? [] : ['groups' => $serializeGroups];
        $item = $this->redis->getItem($name);
        $item->set($this->getProcessedValue($value, $groups));
        $item->tag($tags);

        return $this->redis->save($item);
    }

    public function get($name):? string
    {
        if (!$this->redis->hasItem($name)) {
            return null;
        }

        return $this->redis->getItem($name)->get();
    }

    public function update($name, $value, array $groups = [])
    {
        if (!$this->redis->hasItem($name)) {
            return $this->save($name, $value, serializeGroups: $groups);
        }

        $groups = empty($serializeGroups) ? [] : ['groups' => $serializeGroups];
        $item = $this->redis->getItem($name);
        $item->set(
            $this->getProcessedValue($value, $groups)
        );

        return $this->redis->save($item);
    }

    public function remove($name): bool
    {
        if (!$this->redis->hasItem($name)) {
            return true;
        }

        return $this->redis->delete($name);
    }

    public function clearByTags(array $tags)
    {
        return $this->redis->invalidateTags($tags);
    }

    protected function getProcessedValue($value, array $groups = [])
    {
        if (is_array($value) || is_object($value)) {
            return $this->serializer->serialize($value, JsonEncoder::FORMAT, $groups);
        }

        return $value;
    }
}