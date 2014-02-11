<?php

namespace GeoIO\WKT;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function coords($x, $y, $z = null, $m = null)
    {
        return array(
            'x' => $x,
            'y' => $y,
            'z' => $z,
            'm' => $m
        );
    }
}
