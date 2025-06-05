# cva-php

A PHP clone of the [class-variance-authority (CVA)](https://github.com/joe-bell/cva) utility, inspired by the original npm package. This package provides utility functions for composing CSS class names based on variants, similar to the original JavaScript/TypeScript version.

## Installation

```bash
composer require derweili/cva-php
```

## Usage

### 1. `Clsx::cx` — Class Name Combiner

Combine class names, arrays, and objects into a single string, ignoring falsy values (like `null`, `false`, `0`, empty string, etc.).

```php
use CvaPhp\Clsx;

// Simple usage
$class = Clsx::cx('foo', null, 'bar', ['baz', false, 'qux']);
// $class === 'foo bar baz qux'

// With associative arrays (object syntax)
$class = Clsx::cx(['foo' => true, 'bar' => false, 'baz' => true]);
// $class === 'foo baz'
```

### 2. `Cva::cva` — Class Variance Authority

Create a function that generates class names based on variants and options.

```php
use CvaPhp\Cva;

$button = Cva::cva('button', [
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
    ],
    'defaultVariants' => [
        'intent' => 'primary',
        'size' => 'small',
    ],
]);

// Usage:
echo $button(); // "button button--primary button--small"
echo $button(['intent' => 'secondary']); // "button button--secondary button--small"
echo $button(['size' => 'large']); // "button button--primary button--large button--primary-large"
echo $button(['class' => 'my-custom-class']); // "button button--primary button--small my-custom-class"
```

## Running Tests

```bash
composer test
```