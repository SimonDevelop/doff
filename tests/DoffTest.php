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

        // Good and with chmod option
        $settings = [
          "path" => __DIR__."/data/",
          "chmod" => 0770
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

        $data = $Doff->select("query", ["name" => "%test%"], [
            "on" => "name",
            "order" => "DESC"
        ]);

        $this->assertEquals($data, [
            ["name" => "test 2"],
            ["name" => "test 1"],
            ["name" => "test 0"],
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

        // insert
        $data = $Doff->select("users");
        $this->assertEquals(count($data), 8);

        $result = $Doff->insert("users", [
            "id" => 72,
            "email" => "world@test.fr"
        ]);
        $this->assertEquals($result, true);

        $data = $Doff->select("users");
        $this->assertEquals(count($data), 9);
        $this->assertEquals($data[8], [
            "id" => 72,
            "email" => "world@test.fr"
        ]);

        // delete
        $result = $Doff->delete("users", [
            "id" => 72,
            "email" => "world@test.fr"
        ]);
        $this->assertEquals($result, true);

        $data = $Doff->select("users");
        $this->assertEquals(count($data), 8);

        // fusion
        $array1 = [
            [
                "id" => 1,
                "email" => "world@test1.fr"
            ],
            [
                "id" => 2,
                "email" => "world@test2.fr"
            ],
            [
                "id" => 3,
                "email" => "world@test3.fr"
            ]
        ];
        $array2 = [
            [
                "id" => 3,
                "email" => "world@test3.fr"
            ],
            [
                "id" => 4,
                "email" => "world@test4.fr"
            ],
            [
                "id" => 5,
                "email" => "world@test5.fr"
            ]
        ];
        $array = [$array1, $array2];
        $data = $Doff->fusion($array);
        $this->assertEquals($data, [
            [
                "id" => 1,
                "email" => "world@test1.fr"
            ],
            [
                "id" => 2,
                "email" => "world@test2.fr"
            ],
            [
                "id" => 3,
                "email" => "world@test3.fr"
            ],
            [
                "id" => 4,
                "email" => "world@test4.fr"
            ],
            [
                "id" => 5,
                "email" => "world@test5.fr"
            ]
        ]);

        // setData and remove
        $result = $Doff->setData("remove", [
            "id" => 1,
            "email" => "test@remove.fr"
        ]);
        $this->assertEquals($result, true);
        $this->assertEquals(file_exists($Doff->getPath()."remove.yml"), true);

        $result = $Doff->remove("remove");
        $this->assertEquals($result, true);
        $this->assertEquals(file_exists($Doff->getPath()."remove.yml"), false);
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
