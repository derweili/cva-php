<?php

use PHPUnit\Framework\TestCase;
use CvaPhp\Cva;

class CvaTest extends TestCase
{
    // Without base
    public function testEmptyBaseReturnsEmptyString() {
        $example = Cva::cva();
        $this->assertSame('', $example());
        $this->assertSame('', $example(['aCheekyInvalidProp' => 'lol']));
        $this->assertSame('adhoc-class', $example(['class' => 'adhoc-class']));
        $this->assertSame('adhoc-className', $example(['className' => 'adhoc-className']));
        $this->assertSame('adhoc-class adhoc-className', $example(['class' => 'adhoc-class', 'className' => 'adhoc-className']));
    }
    public function testUndefinedBaseReturnsEmptyString() {
        // PHP does not have 'undefined', so this is equivalent to null
        $example = Cva::cva(null);
        $this->assertSame('', $example());
        $this->assertSame('', $example(['aCheekyInvalidProp' => 'lol']));
        $this->assertSame('adhoc-class', $example(['class' => 'adhoc-class']));
        $this->assertSame('adhoc-className', $example(['className' => 'adhoc-className']));
        $this->assertSame('adhoc-class adhoc-className', $example(['class' => 'adhoc-class', 'className' => 'adhoc-className']));
    }
    public function testNullBaseReturnsEmptyString() {
        $example = Cva::cva(null);
        $this->assertSame('', $example());
        $this->assertSame('', $example(['aCheekyInvalidProp' => 'lol']));
        $this->assertSame('adhoc-class', $example(['class' => 'adhoc-class']));
        $this->assertSame('adhoc-className', $example(['className' => 'adhoc-className']));
        $this->assertSame('adhoc-class adhoc-className', $example(['class' => 'adhoc-class', 'className' => 'adhoc-className']));
    }
    public function testAdhocClassAndClassNameProps() {
        $example = Cva::cva();
        $this->assertSame('adhoc-class', $example(['class' => 'adhoc-class']));
        $this->assertSame('adhoc-className', $example(['className' => 'adhoc-className']));
        $this->assertSame('adhoc-class adhoc-className', $example(['class' => 'adhoc-class', 'className' => 'adhoc-className']));
    }

    // Without defaults
    public function testVariantsStringValues() {
        $button = Cva::cva(null, [
            'variants' => [
                'intent' => [
                    'primary' => 'button--primary',
                    'secondary' => 'button--secondary',
                ],
                'size' => [
                    'small' => 'button--small',
                    'large' => 'button--large',
                ],
            ],
        ]);
        $this->assertSame('', $button());
        $this->assertSame('button--primary', $button(['intent' => 'primary']));
        $this->assertSame('button--secondary', $button(['intent' => 'secondary']));
        $this->assertSame('button--primary button--small', $button(['intent' => 'primary', 'size' => 'small']));
        $this->assertSame('button--secondary button--large', $button(['intent' => 'secondary', 'size' => 'large']));
    }

    public function testVariantsArrayValues() {
        $button = Cva::cva(null, [
            'variants' => [
                'intent' => [
                    'primary' => ['button--primary', 'bg-blue-500'],
                    'secondary' => ['button--secondary', 'bg-white'],
                ],
                'size' => [
                    'small' => ['button--small', 'text-sm'],
                    'large' => ['button--large', 'text-lg'],
                ],
            ],
        ]);
        $this->assertSame('', $button());
        $this->assertSame('button--primary bg-blue-500', $button(['intent' => 'primary']));
        $this->assertSame('button--secondary bg-white', $button(['intent' => 'secondary']));
        $this->assertSame('button--primary bg-blue-500 button--small text-sm', $button(['intent' => 'primary', 'size' => 'small']));
        $this->assertSame('button--secondary bg-white button--large text-lg', $button(['intent' => 'secondary', 'size' => 'large']));
    }

    public function testCompoundVariants() {
        $button = Cva::cva(null, [
            'variants' => [
                'intent' => [
                    'primary' => 'button--primary',
                    'secondary' => 'button--secondary',
                ],
                'size' => [
                    'small' => 'button--small',
                    'large' => 'button--large',
                ],
            ],
            'compoundVariants' => [
                [
                    'intent' => 'primary',
                    'size' => 'large',
                    'class' => 'button--primary-large',
                ],
                [
                    'intent' => 'secondary',
                    'size' => 'small',
                    'class' => 'button--secondary-small',
                ],
            ],
        ]);
        $this->assertSame('', $button());
        $this->assertSame('button--primary', $button(['intent' => 'primary']));
        $this->assertSame('button--secondary', $button(['intent' => 'secondary']));
        $this->assertSame('button--primary button--large button--primary-large', $button(['intent' => 'primary', 'size' => 'large']));
        $this->assertSame('button--secondary button--small button--secondary-small', $button(['intent' => 'secondary', 'size' => 'small']));
    }

    public function testCompoundVariantsWithClassName() {}
    public function testCompoundVariantsWithArray() {}
    public function testCompoundVariantsWithClassNameArray() {
        $button = Cva::cva(null, [
            'variants' => [
                'intent' => [
                    'primary' => 'button--primary',
                    'secondary' => 'button--secondary',
                ],
                'size' => [
                    'small' => 'button--small',
                    'large' => 'button--large',
                ],
            ],
            'compoundVariants' => [
                [
                    'intent' => 'primary',
                    'size' => 'large',
                    'className' => ['button--primary-large', 'uppercase'],
                ],
                [
                    'intent' => 'secondary',
                    'size' => 'small',
                    'className' => ['button--secondary-small', 'lowercase'],
                ],
            ],
        ]);
        $this->assertSame('button--primary button--large button--primary-large uppercase', $button(['intent' => 'primary', 'size' => 'large']));
        $this->assertSame('button--secondary button--small button--secondary-small lowercase', $button(['intent' => 'secondary', 'size' => 'small']));
    }
    public function testDefaultVariants() {}
    public function testDefaultVariantsWithClassName() {}
    public function testDefaultVariantsWithArray() {}
    public function testDefaultVariantsWithClassNameArray() {}

    // With base
    public function testWithBaseClass() {}
    public function testWithBaseClassWithDefaults() {}
    public function testWithBaseClassWithVariants() {}
    public function testWithBaseClassWithCompoundVariants() {}
    public function testWithBaseClassWithDefaultVariants() {}
    public function testWithBaseClassWithAdhocClass() {}
    public function testWithBaseClassWithAdhocClassName() {}

    // With defaults
    public function testWithDefaults() {}
    public function testWithDefaultsWithVariants() {}
    public function testWithDefaultsWithCompoundVariants() {}
    public function testWithDefaultsWithAdhocClass() {}
    public function testWithDefaultsWithAdhocClassName() {}

    // Composing classes
    public function testComposingClasses() {}
    public function testComposingClassesWithVariants() {
        $box = Cva::cva(['box', 'box-border'], [
            'variants' => [
                'margin' => [
                    0 => 'm-0',
                    2 => 'm-2',
                    4 => 'm-4',
                    8 => 'm-8',
                ],
                'padding' => [
                    0 => 'p-0',
                    2 => 'p-2',
                    4 => 'p-4',
                    8 => 'p-8',
                ],
            ],
        ]);
        $cardBase = Cva::cva(['card', 'border-solid', 'border-slate-300', 'rounded'], [
            'variants' => [
                'shadow' => [
                    'md' => 'drop-shadow-md',
                    'lg' => 'drop-shadow-lg',
                    'xl' => 'drop-shadow-xl',
                ],
            ],
        ]);
        $card = function($props = []) use ($box, $cardBase) {
            return CvaPhp\Clsx::cx($box($props), $cardBase($props));
        };
        $this->assertSame('box box-border card border-solid border-slate-300 rounded', $card());
        $this->assertSame('box box-border m-4 card border-solid border-slate-300 rounded', $card(['margin' => 4]));
        $this->assertSame('box box-border card border-solid border-slate-300 rounded drop-shadow-md', $card(['shadow' => 'md']));
    }
    public function testComposingClassesWithDefaultVariants() {
        $box = Cva::cva(['box', 'box-border'], [
            'variants' => [
                'margin' => [
                    0 => 'm-0',
                    2 => 'm-2',
                    4 => 'm-4',
                    8 => 'm-8',
                ],
                'padding' => [
                    0 => 'p-0',
                    2 => 'p-2',
                    4 => 'p-4',
                    8 => 'p-8',
                ],
            ],
            'defaultVariants' => [
                'margin' => 0,
                'padding' => 0,
            ],
        ]);
        $cardBase = Cva::cva(['card', 'border-solid', 'border-slate-300', 'rounded'], [
            'variants' => [
                'shadow' => [
                    'md' => 'drop-shadow-md',
                    'lg' => 'drop-shadow-lg',
                    'xl' => 'drop-shadow-xl',
                ],
            ],
        ]);
        $card = function($props = []) use ($box, $cardBase) {
            return CvaPhp\Clsx::cx($box($props), $cardBase($props));
        };
        $this->assertSame('box box-border m-0 p-0 card border-solid border-slate-300 rounded', $card());
        $this->assertSame('box box-border m-2 p-0 card border-solid border-slate-300 rounded', $card(['margin' => 2]));
        $this->assertSame('box box-border m-0 p-0 card border-solid border-slate-300 rounded drop-shadow-lg', $card(['shadow' => 'lg']));
    }
    public function testComposingClassesWithCompoundVariants() {
        $box = Cva::cva(['box', 'box-border'], [
            'variants' => [
                'margin' => [
                    0 => 'm-0',
                    2 => 'm-2',
                    4 => 'm-4',
                    8 => 'm-8',
                ],
                'padding' => [
                    0 => 'p-0',
                    2 => 'p-2',
                    4 => 'p-4',
                    8 => 'p-8',
                ],
            ],
            'defaultVariants' => [
                'margin' => 0,
                'padding' => 0,
            ],
        ]);
        $cardBase = Cva::cva(['card', 'border-solid', 'border-slate-300', 'rounded'], [
            'variants' => [
                'shadow' => [
                    'md' => 'drop-shadow-md',
                    'lg' => 'drop-shadow-lg',
                    'xl' => 'drop-shadow-xl',
                ],
            ],
            'compoundVariants' => [
                [
                    'shadow' => 'md',
                    'class' => 'card-md',
                ],
            ],
        ]);
        $card = function($props = []) use ($box, $cardBase) {
            return CvaPhp\Clsx::cx($box($props), $cardBase($props));
        };
        $this->assertSame('box box-border m-0 p-0 card border-solid border-slate-300 rounded', $card());
        $this->assertSame('box box-border m-0 p-0 card border-solid border-slate-300 rounded drop-shadow-md card-md', $card(['shadow' => 'md']));
    }

    // Edge cases and type extractor
    public function testEdgeCases() {}
    public function testTypeExtractorOrInvalidProps() {}
}
