[![PHP CI](https://github.com/ForeachQ/gendiff/actions/workflows/php-ci.yml/badge.svg)](https://github.com/ForeachQ/gendiff/actions/workflows/php-ci.yml)
<a href="https://codeclimate.com/github/ForeachQ/gendiff/maintainability"><img src="https://api.codeclimate.com/v1/badges/a176cc0a7d5d5cd1aaf3/maintainability" /></a>
<a href="https://codeclimate.com/github/ForeachQ/gendiff/test_coverage"><img src="https://api.codeclimate.com/v1/badges/a176cc0a7d5d5cd1aaf3/test_coverage" /></a>

# Gendiff

«Gendiff» — a program that finds the difference between two data structures.

## Description

Can be used as a separate CLI application or imported as a php package.

Program gets 2 paths and output format as input and returns structures differences in user defined format.

Utility features:
- Recursive processing;
- Support for different input formats: yaml and json;
- Report generation in the form of plain text, "stylish" and json.

## Requirements

- php 7.4
- composer 2.*

## Installation

### Php package
```bash
composer require foreachq/gendiff
```

### Cli application
- Download package

```bash
composer create-project foreachq/gendiff
```

- Install dependencies

```
make install
```

## Usage

### CLI
```bash
# format plain
$ ./gendiff --format plain path/to/file.yml another/path/file.json

Property 'common.follow' was added with value: false
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group2' was removed

# format by default (stylish)
$ ./gendiff filepath1.json filepath2.json

{
  + follow: false
    setting1: Value 1
  - setting2: 200
  - setting3: true
  + setting3: {
        key: value
    }
  + setting4: blah blah
  + setting5: {
        key5: value5
    }
}

$ ./gendiff -h # for help
```

### Php package
```php
use function Differ\Differ\genDiff;

// formats: stylish (default), plain, json
$diff = genDiff($pathToFile1, $pathToFile2, $format);
print_r($diff);
```

## Asciinemas of usage

- [gendiff-stylish](https://asciinema.org/a/469671)
- [gendiff-plain](https://asciinema.org/a/469672)
- [gendiff-json](https://asciinema.org/a/469673)
