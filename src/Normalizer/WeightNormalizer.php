<?php

namespace App\Normalizer;

use App\Entity\Embeddable\Weight;
use App\Enum\WeightUnit;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class WeightNormalizer implements NormalizerInterface
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    /**
     * @param Weight $data
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): float|string
    {
        $unitParam = $this->requestStack->getCurrentRequest()?->query->get('unit');

        if (empty($unitParam)) {
            $targetUnit = WeightUnit::GRAM;

        } else {
            $unitParam = strtolower($unitParam);
            $targetUnit = WeightUnit::tryFrom($unitParam) ?? WeightUnit::GRAM;
        }

        if (is_null($targetUnit)) {
            return $data->getWeightInGrams();
        }

        return Weight::toUnit($data->getWeightInGrams(), WeightUnit::GRAM, $targetUnit);
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Weight;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Weight::class => true,
        ];
    }
}