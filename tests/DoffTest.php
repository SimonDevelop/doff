<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\Doff;

class DoffTest extends TestCase
{
    /**
     * Constructor test
     */
    public function testInitConstructor()
    {
        $settings = [
          "path" => __DIR__."/data"
        ];
        $Doff = new Doff($settings);
        
        return $Doff;
    }
}
