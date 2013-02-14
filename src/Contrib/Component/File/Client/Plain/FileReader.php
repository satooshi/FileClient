<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\BaseFileClient;

/**
 * File reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReader extends BaseFileClient implements FileReaderInterface
{
    // FileReaderInterface

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\Plain\FileReaderInterface::read()
     */
    public function read()
    {
        if (!$this->file->isReadable()) {
            return false;
        }

        $content = file_get_contents($this->file->getPath());

        // convert encoding
        if (is_string($content) && $this->options['convertEncoding']) {
            return mb_convert_encoding(
                $content,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        return $content;
    }

    // API

    /**
     * Return file content exploded by new line.
     *
     * @return array File contents.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLines()
    {
        if (!$this->file->isReadable()) {
            return false;
        }

        ini_set('auto_detect_line_endings', $this->options['autoDetectLineEnding']);

        $content = file($this->file->getPath(), FILE_IGNORE_NEW_LINES);

        if (!$this->options['convertEncoding']) {
            return $content;
        }

        // convert encoding
        $converted = array();

        foreach ($content as $line) {
            $converted[] = mb_convert_encoding(
                $line,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        return $converted;
    }
}
