<?php

namespace Tests;

use App\Models\User;
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

    protected function actingAsUser(): TestCase
    {
        $this->actingAs(User::factory()->create());
        return $this;
    }

    protected function actingAsAdmin(): TestCase
    {
        $this->actingAs(User::factory()->admin()->create());
        return $this;
    }
}
