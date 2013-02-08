FileClient
==========

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
// construction
$path = '/path/to/file';
$client = new FileClient($path);

// default parameters
$newLine = PHP_EOL;
$throwException = true; // throw exception on runtime error
$autoDetectLineEnding = true; // better line ending handling on Mac
$client = new FileClient($path);

// read
try {
    $content = $client->read();
} catch (\RuntimeException $e) {
    // exception occurs if you don't set $throwException = false on construction
    // and the client could not read a file
}

// write
// exception occurs if the client could not write content to a file
$client->write($content);

// append
// exception occurs if the client could not write content to a file
$client->append($content);

// walk
// exception occurs if the client could not read content to a file
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

```php
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
