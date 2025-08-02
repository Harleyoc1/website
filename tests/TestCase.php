<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    protected function reflectProperty($object, $property): mixed
    {
        try {
            $reflectedClass = new ReflectionClass($object);
            $property = $reflectedClass->getProperty($property);
            $property->setAccessible(true);
            return $property->getValue($object);
        } catch (ReflectionException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
