[![version](https://img.shields.io/badge/Version-0.1.1-brightgreen.svg)](https://github.com/SimonDevelop/doff/releases/tag/0.1.1)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1.3-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/SimonDevelop/doff.svg?branch=master)](https://travis-ci.org/SimonDevelop/doff)
[![GitHub license](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/SimonDevelop/doff/blob/master/LICENSE)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FSimonDevelop%2Fdoff.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FSimonDevelop%2Fdoff?ref=badge_shield)
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
  "chmod" => 0770 // optionnal, octal value (only string or integer type)
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
- Check the [Code of Conduct](https://github.com/SimonDevelop/doff/blob/master/.github/CODE_OF_CONDUCT.md)
- Check the [Contributing file](https://github.com/SimonDevelop/doff/blob/master/.github/CONTRIBUTING.md)
- Check the [Pull Request Template](https://github.com/SimonDevelop/doff/blob/master/.github/PULL_REQUEST_TEMPLATE.md)


## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FSimonDevelop%2Fdoff.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FSimonDevelop%2Fdoff?ref=badge_large)