<?php
namespace Contrib\Component\File\FileHandler\Plain;

use Contrib\Component\File\FileHandler\AbstractFileHandler;
use Contrib\Component\File\File;

/**
 * File line reader.
 *
 * * autoDetectLineEnding: boolean Default is true
 * * convert: boolean Default is false
 * * toEncoding: string Default is 'UTF-8'
 * * fromEncoding: string Default is 'auto'
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class LineReader extends AbstractFileHandler implements LineReaderInterface
{
    /**
     * Constructor.
     *
     * @param string $path    File path.
     * @param array  $options Options.
     */
    public function __construct(File $file, array $options = array())
    {
        $this->file    = $file;
        $this->options = $options + static::getDefaultOptions();

        ini_set('auto_detect_line_endings', $this->options['autoDetectLineEnding']);
    }

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\FileHandler\Plain\LineReaderInterface::read()
     */
    public function read($length = null)
    {
        if ($length === null || !is_int($length)) {
            $line = fgets($this->handle);
        } else {
            $line = fgets($this->handle, $length);
        }

        // convert encoding
        if ($this->options['convert']) {
            return mb_convert_encoding(
                $line,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        return $line;
    }

    /**
     * Open file for read.
     *
     * @return void
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForRead()
    {
        $this->handle = $this->file->openForRead();
    }

    /**
     * Return defualt encoding options.
     *
     * @return array Default encoding options.
     */
    public static function getDefaultOptions()
    {
        return array(
            'autoDetectLineEnding' => true,
            'convert'              => false,
            'toEncoding'           => 'UTF-8',
            'fromEncoding'         => 'auto',
        );
    }
}
