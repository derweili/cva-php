<?php
/**
 * Clsx utility for conditionally joining class names (PHP clone of clsx).
 *
 * @package CvaPhp
 */

namespace CvaPhp;

/**
 * Class Clsx
 *
 * Provides a static method cx for joining class names based on various argument types.
 */
class Clsx {

	/**
	 * Join class names conditionally, similar to the JS clsx utility.
	 *
	 * @param mixed ...$args Arguments to process (strings, arrays, associative arrays, etc).
	 * @return string The joined class names.
	 */
	public static function cx( ...$args ) {
		$classes = [];
		foreach ( $args as $arg ) {
			self::collect( $arg, $classes );
		}
		return trim( implode( ' ', $classes ) );
	}

	/**
	 * Recursively collect class names from the argument.
	 *
	 * @param mixed $arg Argument to process.
	 * @param array $classes Reference to the array collecting class names.
	 * @return void
	 */
	private static function collect( $arg, array &$classes ) {
		if ( is_string( $arg ) ) {
			if ( $arg !== '' ) {
				$classes[] = $arg;
			}
		} elseif ( is_int( $arg ) ) {
			if ( $arg === 1 ) {
				$classes[] = '1';
			} elseif ( $arg !== 0 ) {
				$classes[] = (string) $arg;
			}
		} elseif ( is_float( $arg ) ) {
			if ( $arg === 1.0 ) {
				$classes[] = '1';
			} elseif ( $arg !== 0.0 && ! is_nan( $arg ) ) {
				$classes[] = (string) $arg;
			}
		} elseif ( is_array( $arg ) ) {
			foreach ( $arg as $key => $value ) {
				if ( is_int( $key ) ) {
					self::collect( $value, $classes );
				} elseif (
					is_array( $value )
				) {
					// For associative arrays, include key if value is any array
					$classes[] = $key;
				} elseif (
					$value && ! is_array( $value ) && ! ( is_float( $value ) && is_nan( $value ) )
				) {
					// Or if value is truthy and not NAN
					$classes[] = $key;
				}
			}
		} elseif ( is_object( $arg ) ) {
			$classes[] = is_callable( $arg ) ? 'function' : 'emptyObject';
		} elseif ( is_callable( $arg ) ) {
			$classes[] = 'function';
		}
	}
}
