Geo I/O WKT Parser
==================

[![Build Status](https://github.com/geo-io/wkt-parser/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/geo-io/wkt-parser/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/github/geo-io/wkt-parser/badge.svg?branch=main)](https://coveralls.io/github/geo-io/wkt-parser?branch=main)

A parser which transforms
[Well-known text (WKT)](http://en.wikipedia.org/wiki/Well-known_text)
representations into geometric objects.

```php
class MyFactory implements GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates, $srid = null)
    {
        return MyPoint($coordinates['x'], $coordinates['y']);
    }

    public function createLineString($dimension, array $points, $srid = null)
    {
        return MyLineString($points);
    }

    // ...
}

$factory = MyFactory();
$parser = new GeoIO\WKT\Parser\Parser($factory);

$myLineString = $parse->parse('LINESTRING(1 2, 2 2, 1 1)');
```

Installation
------------

Install [through composer](http://getcomposer.org). Check the
[packagist page](https://packagist.org/packages/geo-io/wkt-parser) for all
available versions.

```bash
composer require geo-io/wkt-parser
```

License
-------

Copyright (c) 2014-2022 Jan Sorgalla. Released under the [MIT License](LICENSE).
