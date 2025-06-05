<?php
/**
 * Unit tests for the Clsx class (clsx PHP clone).
 *
 * @package CvaPhp
 */

use PHPUnit\Framework\TestCase;
use CvaPhp\Clsx;

/**
 * Class ClsxTest
 *
 * @covers \CvaPhp\Clsx
 */
class ClsxTest extends TestCase {

	/**
	 * Test that object keys with truthy values are kept.
	 */
	public function testKeepsObjectKeysWithTruthyValues() {
		$out = Clsx::cx(
			[
				'a' => true,
				'b' => false,
				'c' => 0,
				'd' => null,
				'e' => null,
				'f' => 1,
			]
		);
		$this->assertSame( 'a f', $out );
	}

	/**
	 * Test that arrays of class names are joined and falsy values are ignored.
	 */
	public function testJoinsArraysOfClassNamesAndIgnoresFalsyValues() {
		$out = Clsx::cx( 'a', 0, null, null, true, 1, 'b' );
		$this->assertSame( 'a 1 b', $out );
	}

	/**
	 * Test that heterogenous arguments are supported.
	 */
	public function testSupportsHeterogenousArguments() {
		$this->assertSame( 'a b', Clsx::cx( [ 'a' => true ], 'b', 0 ) );
	}

	/**
	 * Test that the result is trimmed.
	 */
	public function testShouldBeTrimmed() {
		$this->assertSame( 'b', Clsx::cx( '', 'b', [], '' ) );
	}

	/**
	 * Test that empty config returns an empty string.
	 */
	public function testReturnsEmptyStringForEmptyConfig() {
		$this->assertSame( '', Clsx::cx( [] ) );
		$this->assertSame( '', Clsx::cx( '' ) );
	}

	/**
	 * Test that array of class names is supported.
	 */
	public function testSupportsArrayOfClassNames() {
		$this->assertSame( 'a b', Clsx::cx( [ 'a', 'b' ] ) );
	}

	/**
	 * Test that array arguments are joined with string arguments.
	 */
	public function testJoinsArrayArgumentsWithStringArguments() {
		$this->assertSame( 'a b c', Clsx::cx( [ 'a', 'b' ], 'c' ) );
		$this->assertSame( 'c a b', Clsx::cx( 'c', [ 'a', 'b' ] ) );
	}

	/**
	 * Test that multiple array arguments are handled.
	 */
	public function testHandlesMultipleArrayArguments() {
		$this->assertSame( 'a b c d', Clsx::cx( [ 'a', 'b' ], [ 'c', 'd' ] ) );
	}

	/**
	 * Test that arrays with falsy and true values are handled.
	 */
	public function testHandlesArraysWithFalsyAndTrueValues() {
		$this->assertSame( 'a b', Clsx::cx( [ 'a', 0, null, null, false, true, 'b' ] ) );
	}

	/**
	 * Test that arrays that include arrays are handled.
	 */
	public function testHandlesArraysThatIncludeArrays() {
		$this->assertSame( 'a b c', Clsx::cx( [ 'a', [ 'b', 'c' ] ] ) );
	}

	/**
	 * Test that arrays that include objects are handled.
	 */
	public function testHandlesArraysThatIncludeObjects() {
		$this->assertSame(
			'a b',
			Clsx::cx(
				[
					'a',
					[
						'b' => true,
						'c' => false,
					],
				]
			)
		);
	}

	/**
	 * Test that deep array recursion is handled.
	 */
	public function testHandlesDeepArrayRecursion() {
		$this->assertSame( 'a b c d', Clsx::cx( [ 'a', [ 'b', [ 'c', [ 'd' => true ] ] ] ] ) );
	}

	/**
	 * Test that empty arrays are handled.
	 */
	public function testHandlesEmptyArrays() {
		$this->assertSame( 'a', Clsx::cx( 'a', [] ) );
	}

	/**
	 * Test that nested empty arrays are handled.
	 */
	public function testHandlesNestedEmptyArrays() {
		$this->assertSame( 'a', Clsx::cx( 'a', [ [] ] ) );
	}

	/**
	 * Test that all types of truthy and falsy property values are handled.
	 */
	public function testHandlesAllTypesOfTruthyAndFalsyPropertyValues() {
		$out = Clsx::cx(
			[
				// falsy:
				'null'           => null,
				'emptyString'    => '',
				'noNumber'       => NAN,
				'zero'           => 0,
				'negativeZero'   => -0,
				'false'          => false,
				'undefined'      => null,
				// truthy (literally anything else):
				'nonEmptyString' => 'foobar',
				'whitespace'     => ' ',
				'function'       => function () {},
				'emptyObject'    => (object) [],
				'nonEmptyObject' => (object) [
					'a' => 1,
					'b' => 2,
				],
				'emptyList'      => [],
				'nonEmptyList'   => [ 1, 2, 3 ],
				'greaterZero'    => 1,
			]
		);
		$this->assertSame( 'nonEmptyString whitespace function emptyObject nonEmptyObject emptyList nonEmptyList greaterZero', $out );
	}
}
