<?php
/**
 * Cva utility for class variance authority (PHP clone of cva).
 *
 * @package CvaPhp
 */

namespace CvaPhp;

/**
 * Class Cva
 *
 * Provides a static method cva for generating class names based on variants and compound variants.
 */
class Cva {

	/**
	 * Generate a class name function based on base, variants, and compound variants.
	 *
	 * @param mixed $base The base class or classes.
	 * @param array $config Configuration array with 'variants', 'defaultVariants', and 'compoundVariants'.
	 * @return callable Function that takes props and returns a class string.
	 */
	public static function cva( $base = null, $config = [] ) {
		return function ( $props = [] ) use ( $base, $config ) {
			$cx                = [ Clsx::class, 'cx' ];
			$variants          = $config['variants'] ?? null;
			$default_variants  = $config['defaultVariants'] ?? [];
			$compound_variants = $config['compoundVariants'] ?? [];

			// Merge default_variants with props (props take precedence)
			$merged_props = array_merge( $default_variants, $props ?? [] );

			// If no variants, just join base, class, className
			if ( $variants === null ) {
				return $cx( $base, $merged_props['class'] ?? null, $merged_props['className'] ?? null );
			}

			// Compute variant class names
			$get_variant_class_names = [];
			foreach ( $variants as $variant => $variant_options ) {
				$variant_value = $merged_props[ $variant ] ?? null;
				if ( $variant_value === null ) {
					continue;
				}
				if ( isset( $variant_options[ $variant_value ] ) ) {
					$get_variant_class_names[] = $variant_options[ $variant_value ];
				}
			}

			// Remove undefined (null) props for compound_variants
			$props_without_undefined = [];
			foreach ( $merged_props as $key => $value ) {
				if ( $value !== null ) {
					$props_without_undefined[ $key ] = $value;
				}
			}

			// Compute compound variant class names
			$get_compound_variant_class_names = [];
			foreach ( $compound_variants as $compound ) {
				$compound                 = (array) $compound;
				$cv_class                 = $compound['class'] ?? null;
				$cv_class_name            = $compound['className'] ?? null;
				$compound_variant_options = $compound;
				unset( $compound_variant_options['class'], $compound_variant_options['className'] );
				$all_match = true;
				foreach ( $compound_variant_options as $key => $value ) {
					$actual = $props_without_undefined[ $key ] ?? null;
					if ( is_array( $value ) ) {
						if ( ! in_array( $actual, $value, true ) ) {
							$all_match = false;
							break;
						}
					} elseif ( $actual !== $value ) {
						$all_match = false;
						break;
					}
				}
				if ( $all_match ) {
					if ( $cv_class ) {
						$get_compound_variant_class_names[] = $cv_class;
					}
					if ( $cv_class_name ) {
						$get_compound_variant_class_names[] = $cv_class_name;
					}
				}
			}

			return $cx(
				$base,
				$get_variant_class_names,
				$get_compound_variant_class_names,
				$merged_props['class'] ?? null,
				$merged_props['className'] ?? null
			);
		};
	}
}
