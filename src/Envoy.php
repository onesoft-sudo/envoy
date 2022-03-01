<?php

namespace OSN\Envoy;

/**
 * Class Envoy
 *
 * @package OSN\Envoy
 * @author Ar Rakin <rakinar2@gmail.com>
 */
class Envoy
{
    public const CONFIG_ASSIGN_ENV_TO_SERVER = 1;

    protected string $envFile;
    protected array $config;

    public function __construct(?string $envFile = null, array $config = [])
    {
        $this->envFile = $envFile ?? './.env';

        $config[static::CONFIG_ASSIGN_ENV_TO_SERVER] = $config[static::CONFIG_ASSIGN_ENV_TO_SERVER] ?? false;

        $this->config = $config;
    }

    /**
     * @return Entity[]
     */
    public function parse(): array
    {
        $lines = file($this->envFile);
        $entities = [];

        foreach ($lines as $line) {
            if (trim($line) === '') {
                Entity::$line++;
                continue;
            }

            $entities[] = new Entity($line);
        }

        return $entities;
    }

    public function load()
    {
        $entities = $this->parse();

        foreach ($entities as $entity) {
            $_ENV[$entity->field()] = $entity->value();

            if ($this->config[static::CONFIG_ASSIGN_ENV_TO_SERVER]) {
                $_SERVER[$entity->field()] = $entity->value();
            }
        }
    }

    public static function getENV($env)
    {
        return $_ENV[$env] ?? null;
    }
}