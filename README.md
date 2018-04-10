[![version](https://img.shields.io/badge/Version-0.0.2-brightgreen.svg)](https://github.com/SimonDevelop/doff/releases/tag/0.0.2)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1.3-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/SimonDevelop/doff.svg?branch=master)](https://travis-ci.org/SimonDevelop/doff)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/SimonDevelop/array-organize/blob/master/LICENSE)
# doff
DOFF for Data Oriented Flat-File, Library for managing yaml table data via query functions.

```bash
composer require simondevelop/doff
```

## Example

```php
<?php
// Initiate doff
require "vendor/autoload.php";
use SimonDevelop\Doff;

$settings = [
  "path" => "/path/of/data/files/",
  "chmod" => 0770
];
$doff = new Doff($settings);
```

In your `/path/of/data/files`
```yaml
# test.yml
-
    name: 'test 0'
-
    name: 'test 1'
-
    name: 'test 2'
-
    name: '3'
-
    name: '4'
```

```php
<?php
//...

// Example with like query for query.yml
$datas = $doff->select("query", ["name" => "%test%"]);

$datas = [
  ["name" => "test 0"],
  ["name" => "test 1"],
  ["name" => "test 2"]
];
```

Check this [docs](https://github.com/SimonDevelop/doff/blob/master/docs/introduction.md) for more.

#### Go to contribute !
