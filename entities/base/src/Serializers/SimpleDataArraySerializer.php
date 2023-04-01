<?php

namespace InetStudio\AdminPanel\Base\Serializers;

use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract;

/**
 * Class SimpleDataArraySerializer.
 */
class SimpleDataArraySerializer extends DataArraySerializer implements SimpleDataArraySerializerContract
{
    /**
     * {@inheritDoc}
     */
    public function collection(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function item(?string $resourceKey, array $data): array
    {
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function null(): ?array
    {
        return [];
    }
}
