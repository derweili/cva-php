<?php

use PHPUnit\Framework\TestCase;
use CvaPhp\Clsx;

class ClsxTest extends TestCase
{
    public function testKeepsObjectKeysWithTruthyValues()
    {
        $out = Clsx::cx([ 'a' => true, 'b' => false, 'c' => 0, 'd' => null, 'e' => null, 'f' => 1 ]);
        $this->assertSame('a f', $out);
    }

    public function testJoinsArraysOfClassNamesAndIgnoresFalsyValues()
    {
        $out = Clsx::cx('a', 0, null, null, true, 1, 'b');
        $this->assertSame('a 1 b', $out);
    }

    public function testSupportsHeterogenousArguments()
    {
        $this->assertSame('a b', Clsx::cx([ 'a' => true ], 'b', 0));
    }

    public function testShouldBeTrimmed()
    {
        $this->assertSame('b', Clsx::cx('', 'b', [], ''));
    }

    public function testReturnsEmptyStringForEmptyConfig()
    {
        $this->assertSame('', Clsx::cx([]));
        $this->assertSame('', Clsx::cx(''));
    }

    public function testSupportsArrayOfClassNames()
    {
        $this->assertSame('a b', Clsx::cx(['a', 'b']));
    }

    public function testJoinsArrayArgumentsWithStringArguments()
    {
        $this->assertSame('a b c', Clsx::cx(['a', 'b'], 'c'));
        $this->assertSame('c a b', Clsx::cx('c', ['a', 'b']));
    }

    public function testHandlesMultipleArrayArguments()
    {
        $this->assertSame('a b c d', Clsx::cx(['a', 'b'], ['c', 'd']));
    }

    public function testHandlesArraysWithFalsyAndTrueValues()
    {
        $this->assertSame('a b', Clsx::cx(['a', 0, null, null, false, true, 'b']));
    }

    public function testHandlesArraysThatIncludeArrays()
    {
        $this->assertSame('a b c', Clsx::cx(['a', ['b', 'c']]));
    }

    public function testHandlesArraysThatIncludeObjects()
    {
        $this->assertSame('a b', Clsx::cx(['a', [ 'b' => true, 'c' => false ]]));
    }

    public function testHandlesDeepArrayRecursion()
    {
        $this->assertSame('a b c d', Clsx::cx(['a', ['b', ['c', [ 'd' => true ]]]]));
    }

    public function testHandlesEmptyArrays()
    {
        $this->assertSame('a', Clsx::cx('a', []));
    }

    public function testHandlesNestedEmptyArrays()
    {
        $this->assertSame('a', Clsx::cx('a', [[]]));
    }

    public function testHandlesAllTypesOfTruthyAndFalsyPropertyValues()
    {
        $out = Clsx::cx([
            // falsy:
            'null' => null,
            'emptyString' => '',
            'noNumber' => NAN,
            'zero' => 0,
            'negativeZero' => -0,
            'false' => false,
            'undefined' => null,
            // truthy (literally anything else):
            'nonEmptyString' => 'foobar',
            'whitespace' => ' ',
            'function' => function () {},
            'emptyObject' => (object)[],
            'nonEmptyObject' => (object)['a' => 1, 'b' => 2],
            'emptyList' => [],
            'nonEmptyList' => [1, 2, 3],
            'greaterZero' => 1
        ]);
        $this->assertSame('nonEmptyString whitespace function emptyObject nonEmptyObject emptyList nonEmptyList greaterZero', $out);
    }
}
