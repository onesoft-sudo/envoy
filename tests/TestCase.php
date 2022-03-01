<?php

namespace OSN\Envoy\Tests;

use OSN\Envoy\Envoy;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Envoy $envoy;
    protected string $envFile = __DIR__ . "/.env";
    protected bool $createApp = false;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->createApp) {
            $this->envoy = new Envoy($this->envFile);
        }

    }

    protected function tearDown(): void
    {
        if ($this->createApp) {
            unset($this->envoy);
        }

        parent::tearDown();
    }
}