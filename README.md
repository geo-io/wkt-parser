Geo I/O WKT Parser
==================

[![Build Status](https://travis-ci.org/geo-io/wkt-parser.svg?branch=master)](https://travis-ci.org/geo-io/wkt-parser)
[![Coverage Status](https://img.shields.io/coveralls/geo-io/wkt-parser.svg)](https://coveralls.io/r/geo-io/wkt-parser)

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
$parser = new GeoIO\WKT\Parser($factory);

$myLineString = $parse->parse('LINESTRING(1 2, 2 2, 1 1)');
```

Installation
------------

Install [through composer](http://getcomposer.org). Check the
[packagist page](https://packagist.org/packages/geo-io/wkt-parser) for all
available versions.

```json
{
    "require": {
        "geo-io/wkt-parser": "0.1.*@dev"
    }
}
```

License
-------

Geo I/O WKT Parser is released under the [MIT License](LICENSE).
