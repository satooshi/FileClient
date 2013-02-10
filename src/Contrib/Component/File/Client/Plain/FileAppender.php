<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileWriter;

/**
 * File appender.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileAppender extends AbstractFileWriter
{
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileWriter::write()
     */
    public function write($lines)
    {
        if ($this->file->isWritable()) {
            return file_put_contents($this->file->getPath(), $lines, FILE_APPEND | LOCK_EX);
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileWriter::writeLines()
     */
    public function writeLines(array $lines, $length = null)
    {
        if (!$this->initWriter()) {
            return false;
        }

        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->lineHandler->write($line, $length);
        }

        return $bytes;
    }

    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileWriter::initWriter()
     */
    protected function initWriter($format = null)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForAppend();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createWriter($handle, $format);
        }

        return true;
    }
}
