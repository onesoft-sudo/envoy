<?php

namespace OSN\Envoy\Tests\Unit;

use OSN\Envoy\Entity;
use OSN\Envoy\EntityParseErrorException;
use OSN\Envoy\Tests\TestCase;

class ParseENVFileTest extends TestCase
{
    protected bool $createApp = true;

    /** @test */
    public function a_simple_env_file_can_be_parsed()
    {
        $this->envoy->load();

        $this->assertSame($_ENV['VAR1'], 'value');
        $this->assertSame($_ENV['VAR2'], 'value');
        $this->assertSame($_ENV['VAR3'], 'value');
        $this->assertSame($_ENV['VAR4'], ' value ');
        $this->assertSame($_ENV['VAR5'], ' value ');
        $this->assertSame($_ENV['VAR6'], null);
    }
}