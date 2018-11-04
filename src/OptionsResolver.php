<?php

namespace rabbit\consul;
/**
 * Class OptionsResolver
 * @package rabbit\consul
 */
final class OptionsResolver
{
    /**
     * @param array $options
     * @param array $availableOptions
     * @return array
     */
    public static function resolve(array $options, array $availableOptions): array
    {
        return array_intersect_key($options, array_flip($availableOptions));
    }
}
