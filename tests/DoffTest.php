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
        // Empty
        $testException = false;
        try {
            $Doff = new Doff();
        } catch (\Exception $e) {
            $testException = true;
        }
        $this->assertEquals(true, $testException);

        // Invalide path
        $testException = false;
        try {
            $Doff = new Doff(["path" => __DIR__."/test"]);
        } catch (\Exception $e) {
            $testException = true;
        }
        $this->assertEquals(true, $testException);

        // Good
        $settings = [
          "path" => __DIR__."/data/"
        ];
        $Doff = new Doff($settings);

        return $Doff;
    }

    /**
     * query functions test
     * @depends testInitConstructor
     */
    public function testQueryFunctions($Doff)
    {
        $data = $Doff->select("query", ["name" => "test 2"]);
        $this->assertEquals($data[0], ["name" => "test 2"]);

        $data = $Doff->select("query", ["name" => "%test%"]);
        $this->assertEquals($data, [
            ["name" => "test 0"],
            ["name" => "test 1"],
            ["name" => "test 2"],
        ]);
    }

    /**
     * Getters and Setters test
     * @depends testInitConstructor
     */
    public function testGetterSetter($Doff)
    {
        $settings = [
          "path" => __DIR__."/data/"
        ];
        $this->assertEquals($settings["path"], $Doff->getPath());
        $data = $Doff->getData("test");

        $this->assertEquals($data[0]['name'], "test 0");
        $this->assertEquals($data[1]['name'], "test 1");
    }
}
