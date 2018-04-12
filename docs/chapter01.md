# Query functions

### getData (string $dataName)
```php
<?php
// your test.yml
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@hotmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$data = $obj->getData("test");

// $data return this array
// $data = [
//     [
//         "id" => 1,
//         "email" => "test@gmail.com"
//     ],
//     [
//         "id" => 2,
//         "email" => "test@hotmail.com"
//     ],
//     [
//         "id" => 42,
//         "email" => "test@horyzone.fr"
//     ]
// ];
```

### setData (string $dataName, array $data)
```php
<?php
// your test.yml before setData
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@hotmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$obj->setData("test", [
    [
        "id" => 1,
        "name" => "test"
    ]
]);

// your test.yml after setData
// -
//     id: 1
//     name: test
```

### select (string $dataName, array $where = [], array $order = [])
```php
<?php
// your test.yml
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@gmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$data = $obj->select("test", [
    "email" => "test@gmail.com"
], [
    "on" => "id",
    "order" => "DESC"
]);

// $data return this array
// $data = [
//     [
//         "id" => 2,
//         "email" => "test@gmail.com"
//     ],
//     [
//         "id" => 1,
//         "email" => "test@gmail.com"
//     ]
// ];
```

### update (string $dataName, array $update, array $where = [])
```php
<?php
// your test.yml before update
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@gmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$data = $obj->update("test", [
    "email" => "test@hotmail.com"
], [
    "id" => 2
]);

// your test.yml after update
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@hotmail.com
// -
//     id: 42
//     email: test@horyzone.fr
```

### insert (string $dataName, array $insert)
```php
<?php
// your test.yml before insert
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@gmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$data = $obj->insert("test", [
    "id" => 66
    "email" => "test@test.com"
]);

// your test.yml after insert
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@hotmail.com
// -
//     id: 42
//     email: test@horyzone.fr
// -
//     id: 66
//     email: test@test.com
```

### delete (string $dataName, array $where)
```php
<?php
// your test.yml before delete
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@gmail.com
// -
//     id: 42
//     email: test@horyzone.fr

$data = $obj->delete("test", [
    "id" => 42
]);

// your test.yml after delete
// -
//     id: 1
//     email: test@gmail.com
// -
//     id: 2
//     email: test@hotmail.com
```

### fusion (array $datas)
```php
<?php
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

$merged = $obj->fusion([$array1, $array2]);

// $merged return this array
// $merged = [
//     [
//         "id" => 1,
//         "email" => "test@gmail.com"
//     ],
//     [
//         "id" => 2,
//         "email" => "test@hotmail.com"
//     ],
//     [
//         "id" => 42,
//         "email" => "test@horyzone.fr"
//     ],
//     [
//         "id" => 57,
//         "email" => "test@test.com"
//     ]
// ];
```

### remove (string $dataName)
```php
<?php
$obj->remove("test");
// delete test.yml file if exist

```

| Introduction |
| :---------------------: |
| [Introduction](https://github.com/SimonDevelop/doff/blob/master/docs/introduction.md) |
