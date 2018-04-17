<?php

namespace SimonDevelop\Test;

use \PHPUnit\Framework\TestCase;
use \SimonDevelop\DoffFacade;

class DoffFacadeTest extends TestCase
{
    /**
     * Fusion function test
     */
    public function testFusion()
    {
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
        $data = DoffFacade::fusion($array);
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
    }

    /**
     * Fission function test
     */
    public function testFission()
    {
        $array1 = [
            [
                "id" => 1,
                "email" => "test@gmail.com"
            ],
            [
                "id" => 2,
                "email" => "test@hotmail.com"
            ],
            [
                "id" => 42,
                "email" => "test@horyzone.fr"
            ]
        ];
        $array2 = [
            [
                "id" => 42,
                "email" => "test@horyzone.fr"
            ],
            [
                "id" => 57,
                "email" => "test@test.com"
            ]
        ];
        $data = DoffFacade::fission($array1, $array2);
        $this->assertEquals($data, [
            [
                "id" => 1,
                "email" => "test@gmail.com"
            ],
            [
                "id" => 2,
                "email" => "test@hotmail.com"
            ]
        ]);
    }
}
