<?php

namespace OSN\Envoy\Tests\Unit;

use OSN\Envoy\Entity;
use OSN\Envoy\EntityParseErrorException;
use OSN\Envoy\Tests\TestCase;

class ParseEntityTest extends TestCase
{
    /** @test */
    public function a_basic_line_can_be_parsed()
    {
        $entity = new Entity("FIELD=value");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_with_space_before_equal_sign_can_be_parsed()
    {
        $entity = new Entity("FIELD =value");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_with_space_after_equal_sign_can_be_parsed()
    {
        $entity = new Entity("FIELD= value");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_without_a_value_can_be_parsed()
    {
        $entity = new Entity("FIELD=");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), null);
    }

    /** @test */
    public function a_line_with_double_quoted_value_can_be_parsed()
    {
        $entity = new Entity("FIELD=\"value\"");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_with_single_quoted_value_can_be_parsed()
    {
        $entity = new Entity("FIELD='value'");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_with_quoted_value_and_spaces_can_be_parsed()
    {
        $entity = new Entity("FIELD=' val ue '");

        $this->assertSame($entity->field(), "FIELD");
        $this->assertSame($entity->value(), " val ue ");
    }

    /** @test */
    public function a_line_with_spaced_field_name_can_be_parsed()
    {
        $entity = new Entity("F IE LD=value");

        $this->assertSame($entity->field(), "F IE LD");
        $this->assertSame($entity->value(), "value");
    }

    /** @test */
    public function a_line_without_an_equal_sign_can_not_be_parsed()
    {
        $this->expectException(EntityParseErrorException::class);
        $this->expectExceptionMessageMatches("/Syntax error: Unexpected token 'F' at line (\d+) (.*)/");

        new Entity("F IE LD value");
    }

    /** @test */
    public function a_line_without_a_field_name_can_not_be_parsed()
    {
        $this->expectException(EntityParseErrorException::class);
        $this->expectExceptionMessageMatches("/Syntax error: Unexpected token '=' at line (\d+) (.*)/");

        new Entity("=value");
    }

    /** @test */
    public function a_line_with_multiple_equal_signs_can_be_parsed()
    {
        $entity = new Entity("DSN=mysql:lost=localhost;dbname=something");

        $this->assertSame($entity->field(), "DSN");
        $this->assertSame($entity->value(), "mysql:lost=localhost;dbname=something");
    }

    /** @test */
    public function mismatching_quotes_should_not_be_removed()
    {
        $entity1 = new Entity("DSN=mysql:lost=localhost;dbname=something'");
        $entity2 = new Entity("DSN=\"mysql:lost=localhost;dbname=something");

        $this->assertSame($entity1->value(), "mysql:lost=localhost;dbname=something'");
        $this->assertSame($entity2->value(), "\"mysql:lost=localhost;dbname=something");
    }
}