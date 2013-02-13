<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\BaseFileClient;

/**
 * File appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileAppender extends BaseFileClient implements FileWriterInterface
{
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\Plain\FileWriterInterface::write()
     */
    public function write($lines)
    {
        if (!isset($this->file)) {
            throw new \RuntimeException('File is not set.');
        }

        // convert encoding
        if ($this->options['convertEncoding']) {
            $lines = mb_convert_encoding(
                $lines,
                $this->options['toEncoding'],
                $this->options['fromEncoding']
            );
        }

        if ($this->file->isWritable()) {
            return file_put_contents($this->file->getPath(), $lines, FILE_APPEND | LOCK_EX);
        }

        return false;
    }
}
