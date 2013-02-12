FileClient
==========

[![Build Status](https://travis-ci.org/satooshi/FileClient.png?branch=master)](https://travis-ci.org/satooshi/FileClient)

FileClient object can read  from or write content to a file. And you can handle file read process for every line as you like by passing a callback or a closure to walk() method. This method is intended for database manipulation. For example, you can insert or update record from a read line.

# Installation

To install FileClient with Composer just add the following to your composer.json file:

```js
// composer.json
{
    // ...
    require: {
        // ...
        "satooshi/file-client": "dev-master"
    }
}
```

Then, you can install the new dependencies by running Composer’s update command from the directory where your composer.json file is located:

```sh
# install
$ php composer.phar install
# update
$ php composer.phar update satooshi/file-client

# or you can simply execute composer command if you set composer command to
# your PATH environment variable
$ composer install
$ composer update satooshi/file-client
```

Packagist page for this library is [https://packagist.org/packages/satooshi/file-client](https://packagist.org/packages/satooshi/file-client)

Or you can use git clone

```sh
# HTTP
$ git clone https://github.com/satooshi/FileClient.git
# SSH
$ git clone git@github.com:satooshi/FileClient.git
```

# Usage

## plain text file

### construction
```php
<?php

use Contrib\Component\File\Client\Plain\FileReader;

// construction
$path = '/path/to/file';
$client = new FileReader($path);

// default parameters
$options = array(
    'newLine'              => PHP_EOL,
    'throwException'       => true, // throw exception on runtime error
    'autoDetectLineEnding' => true, // better line ending handling on Mac
);

$client = new FileReader($path, $options);
```

### read

```php
<?php

use Contrib\Component\File\Client\Plain\FileReader;

$path = '/path/to/file';
$client = new FileReader($path);

// read
$content = $client->read();
$lines = $client->readLines();
```

### write
```php
<?php

use Contrib\Component\File\Client\Plain\FileWriter;

$path = '/path/to/file';
$client = new FileWriter($path);

// write
$content = 'hello world!';
$client->write($content);

$lines = array(
    'line1',
    'line2',
);
$client->writeLines($lines);
```

### append
```php
<?php

use Contrib\Component\File\Client\Plain\FileAppender;

$path = '/path/to/file';
$client = new FileAppender($path);

// append
$content = 'hello world!';
$client->write($content);

$lines = array(
    'line1',
    'line2',
);
$client->writeLines($lines);
```

### walk
```php
<?php

use Contrib\Component\File\Client\Plain\FileReaderIterator;

// default parameters
$options = array(
    'newLine'              => PHP_EOL,
    'throwException'       => true, // throw exception on runtime error
    'autoDetectLineEnding' => true, // better line ending handling on Mac
    'skipEmptyCount'       => true,
    'limit'                => 0,
    'offset'               => 0,
);

$path = '/path/to/file';
$client = new FileReaderIterator($path, $options);

// walk
$client->walk(
    funtion ($line, $numLine) {
        if ($numLine === 1) {
            // do something at line 1
       }
    }
);
```

## LTSV file

Labeled Tab-separated Values (LTSV) file format is introduced by Hatena engineer ([blog post in Japanese](http://stanaka.hatenablog.com/entry/2013/02/05/214833)) and is variant TSV file format. You can find a suitable library for several programming language and see description at [ltsv.org](http://ltsv.org/).

```php
<?php

use Contrib\Component\Serializer\Factory;
use Contrib\Component\File\Client\GenericFileClient;

$path = '/path/to/log.ltsv';
$serializer = Factory::createSerializer();
$client = new GenericFileClient($path, $serializer);

```
