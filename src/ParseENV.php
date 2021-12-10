<?php

namespace OSN\Envoy;

use http\Encoding\Stream;

/**
 * Class ParseENV
 * The main class where the magic happens!
 *
 * @package OSN\Envoy
 * @author Ar Rakin <rakinar2@gmail.com>
 */
class ParseENV
{
    /** @var int */
    protected int $line = 0;

    /** @var string */
    protected string $filename;

    /**
     * Parse a single line.
     * Throws exception if there is a parse error.
     *
     * @param string $line
     * @throws Exception
     * @return array|bool
     */
    public function parseLine(string $line)
    {
        $exp = explode("=", $line);

        if(count($exp) == 1 && trim($exp[0]) === ''){
            return false;
        }

        if (count($exp) < 2){
            throw new Exception("Parse error: Syntax error: Expecting '=' on line {$this->line}. File: \"{$this->filename}\"", 1);
        }

        $var = strtoupper(trim($exp[0]));
        array_shift($exp);
        $value = trim(implode("=", $exp));

        if ($var === ""){
            throw new Exception("Parse error: Syntax error: Unexpected '=' on line {$this->line}. File: \"{$this->filename}\"", 2);
        }

        return ["var" => "$var", "value" => "$value"];
    }

    /**
     * Parse a file in the given path.
     *
     * @param string $filepath
     * @throws Exception
     * @return array
     */
    public function parseFile(string $filepath): array
    {
        $this->filename = $filepath;

        $contents = trim(file_get_contents($filepath));
        $lines = explode("<br />", nl2br($contents));
        $parsed = [];

        foreach ($lines as $line) {
            $this->line++;
            $parsedLine = $this->parseLine($line);


            if ($parsedLine === false) {
                continue;
            }

            $parsed[$parsedLine["var"]] = $parsedLine["value"];
        }

        return $parsed;
    }
}