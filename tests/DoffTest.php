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
        // select
        $data = $Doff->select("query", ["name" => "test 2"]);
        $this->assertEquals($data[0], ["name" => "test 2"]);

        $data = $Doff->select("query", ["name" => "%test%"]);
        $this->assertEquals($data, [
            ["name" => "test 0"],
            ["name" => "test 1"],
            ["name" => "test 2"],
        ]);

        // update
        $data = $Doff->select("users", ["id" => 66]);
        $this->assertEquals($data[0]["email"], "test4@gmail.com");

        $result = $Doff->update("users", ["email" => "test@horyzone.fr"], ["id" => 66]);
        $this->assertEquals($result, true);

        $data2 = $Doff->select("users", ["id" => 66]);
        $this->assertEquals($data2[0]["email"], "test@horyzone.fr");

        $result = $Doff->update("users", ["email" => "test4@gmail.com"], ["id" => 66]);
        $this->assertEquals($result, true);
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
        $data = $Doff->getData("query");

        $this->assertEquals($data[0]['name'], "test 0");
        $this->assertEquals($data[1]['name'], "test 1");
        $this->assertEquals($data[2]['name'], "test 2");
        $this->assertEquals($data[3]['name'], "3");
        $this->assertEquals($data[4]['name'], "4");
    }
}
