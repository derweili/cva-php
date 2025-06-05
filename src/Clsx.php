<?php

namespace CvaPhp;

class Clsx
{
    public static function cx(...$args)
    {
        $classes = [];
        foreach ($args as $arg) {
            self::collect($arg, $classes);
        }
        return trim(implode(' ', $classes));
    }

    private static function collect($arg, array &$classes)
    {
        if (is_string($arg)) {
            if ($arg !== '') {
                $classes[] = $arg;
            }
        } elseif (is_int($arg)) {
            if ($arg === 1) {
                $classes[] = '1';
            } elseif ($arg !== 0) {
                $classes[] = (string)$arg;
            }
        } elseif (is_float($arg)) {
            if ($arg === 1.0) {
                $classes[] = '1';
            } elseif ($arg !== 0.0 && !is_nan($arg)) {
                $classes[] = (string)$arg;
            }
        } elseif (is_array($arg)) {
            foreach ($arg as $key => $value) {
                if (is_int($key)) {
                    self::collect($value, $classes);
                } else {
                    // For associative arrays, include key if value is any array, or if value is truthy and not NAN
                    if (
                        is_array($value) ||
                        ($value && !is_array($value) && !(is_float($value) && is_nan($value)))
                    ) {
                        $classes[] = $key;
                    }
                }
            }
        } elseif (is_object($arg)) {
            $classes[] = is_callable($arg) ? 'function' : 'emptyObject';
        } elseif (is_callable($arg)) {
            $classes[] = 'function';
        }
    }
}

if (!function_exists('is_nan')) {
    function is_nan($val) {
        return is_float($val) && ($val !== $val);
    }
}
