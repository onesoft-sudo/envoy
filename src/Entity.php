<?php


namespace OSN\Envoy;


class Entity
{
    protected string $raw;
    protected string $field;
    protected ?string $value;
    public static int $line = 0;

    public function __construct(string $line)
    {
        $this->raw = $line;
        static::$line++;
        $this->parse();
    }

    /**
     * @return string
     */
    public function raw(): string
    {
        return $this->raw;
    }

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

    protected function removeQuotes($value)
    {
        if (($value[0] === '"' && $value[-1] === '"') || ($value[0] === "'" && $value[-1] === "'")) {
            $value = substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * @return string
     */
    public function field(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function value(): ?string
    {
        return $this->value;
    }
}