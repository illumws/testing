<?php

namespace Alms\Testing\Support;


class Str
{
    public static function contains(string $haystack, array|string $needles, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $haystack = mb_strtolower($haystack);
        }

        if (! is_iterable($needles)) {
            $needles = (array) $needles;
        }

        foreach ($needles as $needle) {
            if ($ignoreCase) {
                $needle = mb_strtolower($needle);
            }

            if ($needle !== '' && str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    public static function substr($string, $start, $length = null, $encoding = 'UTF-8'): string
    {
        return mb_substr($string, $start, $length, $encoding);
    }
}
