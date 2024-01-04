<?php

namespace Alms\Testing\Support;

class Util
{
    public static function value(mixed $value, mixed ...$args)
    {
        return $value instanceof \Closure ? $value(...$args) : $value;
    }
}