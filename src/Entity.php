<?php
/*
 * Copyright 2020-2022 The OSN Software Foundation, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OSN\Envoy;

/**
 * The Entity class.
 * An "Entity" is a wrapper of the each individual line in the env file.
 *
 * @package OSN\Envoy
 * @author Ar Rakin <rakinar2@gmail.com>
 */
class Entity
{
    /**
     * The raw line.
     *
     * @var string
     */
    protected string $raw;

    /**
     * The field name.
     *
     * @var string
     */
    protected string $field;

    /**
     * The field value.
     *
     * @var string|null
     */
    protected ?string $value;

    /**
     * Line count.
     *
     * @var int
     */
    public static int $line = 0;

    /**
     * Entity constructor.
     *
     * @param string $line
     */
    public function __construct(string $line)
    {
        $this->raw = $line;
        static::$line++;
        $this->parse();
    }

    /**
     * Get the raw line.
     *
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }

    /**
     * Parse the raw line.
     *
     * @return void
     */
    public function parse()
    {
        $equalPos = strpos($this->raw, '=');

        if ($equalPos !== false && $equalPos <= 0) {
            throw new EntityParseErrorException("Syntax error: Unexpected token '=' at line " . static::$line . " in ENV file");
        }

        if ($equalPos === false) {
            throw new EntityParseErrorException("Syntax error: Unexpected token '". ($this->raw[0] ?? 'NEWLINE') . "' at line " . static::$line . " in ENV file");
        }

        $field = substr($this->raw, 0, $equalPos);
        $value = substr($this->raw, $equalPos + 1);

        if ($value === '' || trim($value) === '') {
            $value = null;
        }

        $this->field = trim($field);
        $this->value = $value === null ? null : $this->removeQuotes(trim($value));
    }

    /**
     * Remove extra quotes from a string start and end.
     *
     * @param $value
     * @return false|mixed|string
     */
    protected function removeQuotes($value)
    {
        if (($value[0] === '"' && $value[-1] === '"') || ($value[0] === "'" && $value[-1] === "'")) {
            $value = substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Get the field name.
     *
     * @return string
     */
    public function field(): string
    {
        return $this->field;
    }

    /**
     * Get the field value.
     *
     * @return string|null
     */
    public function value(): ?string
    {
        return $this->value;
    }
}