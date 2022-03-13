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
 * Class Envoy, the main class.
 *
 * @package OSN\Envoy
 * @author Ar Rakin <rakinar2@gmail.com>
 */
class Envoy
{
    public const CONFIG_ASSIGN_ENV_TO_SERVER = 1;

    /**
     * The full env file path.
     *
     * @var string
     */
    protected string $envFile;

    /**
     * Envoy-related configuration storage.
     *
     * @var array
     */
    protected array $config;

    /**
     * Envoy constructor.
     *
     * @param string|null $envFile
     * @param array $config
     */
    public function __construct(?string $envFile = null, array $config = [])
    {
        $this->envFile = $envFile ?? './.env';
        $config[static::CONFIG_ASSIGN_ENV_TO_SERVER] = $config[static::CONFIG_ASSIGN_ENV_TO_SERVER] ?? false;
        $this->config = $config;
    }

    /**
     * Parse the env file and return an array of Entities.
     *
     * @return Entity[]
     */
    public function parse(): array
    {
        $lines = file($this->envFile);
        $entities = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || $trimmed[0] === '#') {
                Entity::$line++;
                continue;
            }

            $entities[] = new Entity($line);
        }

        return $entities;
    }

    /**
     * Parse the env file and then load all data to the super-global variables.
     *
     * @return void
     */
    public function load(): void
    {
        $entities = $this->parse();

        foreach ($entities as $entity) {
            $_ENV[$entity->field()] = $entity->value();

            if ($this->config[static::CONFIG_ASSIGN_ENV_TO_SERVER]) {
                $_SERVER[$entity->field()] = $entity->value();
            }
        }
    }

    /**
     * A safe wrapper for getting environment configuration values.
     *
     * @param mixed $env
     * @return mixed|null
     */
    public static function getENV($env)
    {
        return $_ENV[$env] ?? null;
    }
}