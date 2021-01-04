<?php

namespace App\Serializer\Normalizer;

use App\Entity\Plant;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PlantNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param mixed|Plant $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'scientificName' => $object->getScientificName(),
            'commonName' => $object->getCommonName(),
            'imageUrl' => $object->getImageUrl(),
            'information' => $object->getInformation(),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Plant;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}