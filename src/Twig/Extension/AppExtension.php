<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('cls', function (array|string $value): string
            {
                if (is_array($value)) {
                    return trim(implode(' ', $value));
                }

                return $value;
            }),
        ];
    }
}
