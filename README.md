# dcarbone/gohttp

Golang-inspired HTTP helpers for PHP, including:

- HTTP status code constants
- HTTP method constants
- status text / status name helpers
- a small `URL\Values` container for query-style key/value lists

## Requirements

- PHP 8.2+
- `ext-json`

## Installation

```bash
composer require dcarbone/gohttp
```

## Usage

### Status helpers

```php
<?php declare(strict_types=1);

use DCarbone\Go\HTTP\HTTP;

echo HTTP::StatusOK; // 200
echo HTTP::StatusText(HTTP::StatusNotFound); // "Not Found"
echo HTTP::StatusName(HTTP::StatusNotFound); // "NotFound"
```

### Namespace functions

The package also provides namespaced functions:

```php
<?php declare(strict_types=1);

use function DCarbone\Go\HTTP\StatusName;
use function DCarbone\Go\HTTP\StatusText;

echo StatusText(418); // "I'm a teapot"
echo StatusName(418); // "Teapot"
```

### Methods

```php
<?php declare(strict_types=1);

use DCarbone\Go\HTTP\HTTP;

$methods = [
    HTTP::MethodGet,
    HTTP::MethodPost,
    HTTP::MethodPut,
    HTTP::MethodPatch,
    HTTP::MethodDelete,
];
```

### URL values

```php
<?php declare(strict_types=1);

use DCarbone\Go\HTTP\URL\Values;

$values = new Values();
$values->add('filter', 'active');
$values->add('filter', 'recent');
$values->set('page', '1');

echo $values->get('filter'); // "active"
print_r($values->getAll('filter')); // ["active", "recent"]

echo (string)$values; // "filter=active&filter=recent&page=1"
```

`Values` implements `Iterator`, `ArrayAccess`, `Countable`, and `JsonSerializable`.

## Development

Install dependencies:

```bash
composer install
```

Run unit tests:

```bash
composer test
```

## License

MIT
