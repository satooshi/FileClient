FileClient
==========

[![Build Status](https://travis-ci.org/satooshi/FileClient.png?branch=master)](https://travis-ci.org/satooshi/FileClient)

FileClient for php (plain text, LTSV).

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
```php
<?php

use Contrib\Component\File\Client\FileClient;

// construction
$path = '/path/to/file';
$client = new FileClient($path);

// default parameters
$newLine = PHP_EOL;
$throwException = true; // throw exception on runtime error
$autoDetectLineEnding = true; // better line ending handling on Mac
$client = new FileClient($path);

// read
$content = $client->read();

// write
$client->write($content);

// append
$client->append($content);

// walk
$client->walk(
    funtion ($line, $numLine) {
        if ($numLine === 1) {
            // do something at line 1
       }
    }
);

// default parameters
$skipEmptyCount = true; 
$limit = -1;  // All lines
$offset = 0; // read from first line

$client->walk(
    funtion ($line, $numLine) {
        if ($numLine === 1) {
            // do something at line 1
        }
    },
    $skipEmptyCount,
    $limit,
    $offset
);
```

## LTSV file

Labeled Tab-separated Values (LTSV) file format is introduced by Hatena engineer ([blog post in Japanese](http://stanaka.hatenablog.com/entry/2013/02/05/214833)) and is variant TSV file format. You can find a suitable library for several programming language and see description at [ltsv.org](http://ltsv.org/).

```php
<?php

use Contrib\Component\File\Client\LtsvFileClient;

$path = '/path/to/log.ltsv';
$client = new LtsvFileClient($path);

// read
// return parsed LTSV items
$content = $client->read();

// write LTSV items
$client->write($content);

// append LTSV items
$client->append($content);
```
