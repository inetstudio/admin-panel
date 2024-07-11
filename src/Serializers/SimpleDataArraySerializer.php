<?php

namespace InetStudio\AdminPanel\Serializers;

use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\AdminPanel\Contracts\Serializers\SimpleDataArraySerializerContract;

/**
 * Class SimpleDataArraySerializer.
 */
class SimpleDataArraySerializer extends DataArraySerializer implements SimpleDataArraySerializerContract
{
    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array $data
     *
     * @return array
     */
    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * Serialize null resource.
     *
     * @return array
     */
    public function null(): ?array
    {
        return [];
    }
}
