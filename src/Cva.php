<?php

namespace CvaPhp;

class Cva
{
    public static function cva($base = null, $config = [])
    {
        return function ($props = []) use ($base, $config) {
            $cx = [Clsx::class, 'cx'];
            $variants = $config['variants'] ?? null;
            $defaultVariants = $config['defaultVariants'] ?? [];
            $compoundVariants = $config['compoundVariants'] ?? [];

            // If no variants, just join base, class, className
            if ($variants === null) {
                return $cx($base, $props['class'] ?? null, $props['className'] ?? null);
            }

            // Compute variant class names
            $getVariantClassNames = [];
            foreach ($variants as $variant => $variantOptions) {
                $variantProp = $props[$variant] ?? null;
                $defaultVariantProp = $defaultVariants[$variant] ?? null;
                if ($variantProp === null) {
                    continue;
                }
                $variantKey = ($variantProp !== null && $variantProp !== '') ? $variantProp : $defaultVariantProp;
                if ($variantKey !== null && isset($variantOptions[$variantKey])) {
                    $getVariantClassNames[] = $variantOptions[$variantKey];
                }
            }

            // Remove undefined (null) props for compoundVariants
            $propsWithoutUndefined = [];
            foreach ($props as $key => $value) {
                if ($value !== null) {
                    $propsWithoutUndefined[$key] = $value;
                }
            }

            // Compute compound variant class names
            $getCompoundVariantClassNames = [];
            foreach ($compoundVariants as $compound) {
                $compound = (array)$compound;
                $cvClass = $compound['class'] ?? null;
                $cvClassName = $compound['className'] ?? null;
                $compoundVariantOptions = $compound;
                unset($compoundVariantOptions['class'], $compoundVariantOptions['className']);
                $allMatch = true;
                foreach ($compoundVariantOptions as $key => $value) {
                    $actual = $propsWithoutUndefined[$key] ?? ($defaultVariants[$key] ?? null);
                    if (is_array($value)) {
                        if (!in_array($actual, $value, true)) {
                            $allMatch = false;
                            break;
                        }
                    } else {
                        if ($actual !== $value) {
                            $allMatch = false;
                            break;
                        }
                    }
                }
                if ($allMatch) {
                    if ($cvClass) $getCompoundVariantClassNames[] = $cvClass;
                    if ($cvClassName) $getCompoundVariantClassNames[] = $cvClassName;
                }
            }

            return $cx(
                $base,
                $getVariantClassNames,
                $getCompoundVariantClassNames,
                $props['class'] ?? null,
                $props['className'] ?? null
            );
        };
    }
}
