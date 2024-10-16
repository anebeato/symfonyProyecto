<?php

namespace App\Serializer\Normalizer;

use App\Entity\Curso;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class CursoNormalizer implements ContextAwareNormalizerInterface, ContextAwareDenormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$this->supportsNormalization($object, $format)) {
            throw new \InvalidArgumentException('Unsupported object type for normalization');
        }

        return [
            'id' => $object->getId(),
            'nombre' => $object->getNombre(),
        ];
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (!$this->supportsDenormalization($data, $class, $format)) {
            throw new \InvalidArgumentException('Unsupported data type for denormalization');
        }

        $curso = new Curso();
        $curso->setNombre($data['nombre']);

        return $curso;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Curso;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Curso::class;
    }
}
