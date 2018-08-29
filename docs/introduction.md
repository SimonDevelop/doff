# Doff references

### __constructor (array $settings = [])
```php
<?php

// Settings
$settings = [
    "path" => "/path/of/data/files/",
    "chmod" => 0770, // optionnal, octal value (only string or integer type)
    "chown" => "userUnix", // optionnal
    "chgrp" => "groupUnix" // optionnal
];

$obj = new Doff($settings);
```

- [Query functions](https://github.com/SimonDevelop/doff/blob/master/docs/chapter01.md)
