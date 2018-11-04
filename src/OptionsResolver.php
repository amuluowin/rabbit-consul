<?php

namespace rabbit\consul;

final class OptionsResolver
{
    public static function resolve(array $options, array $availableOptions)
    {
        return array_intersect_key($options, array_flip($availableOptions));
    }
}