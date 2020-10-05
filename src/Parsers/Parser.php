<?php

namespace App\Parsers;

/**
 * Parser
 * @author    Gigabyte Software Limited
 * @copyright Gigabyte Software Limited
 */
interface Parser
{
    public function parse(string $path);
}
