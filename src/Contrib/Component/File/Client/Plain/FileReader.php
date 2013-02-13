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
    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\Plain\FileReaderInterface::read()
     */
    public function read($explode = false)
    {
        if (!isset($this->file)) {
            throw new \RuntimeException('File is not set.');
        }

        if ($this->file->isReadable()) {
            if ($explode) {
                ini_set('auto_detect_line_endings', $this->options['autoDetectLineEnding']);
                $content = file($this->file->getPath(), FILE_IGNORE_NEW_LINES);
            } else {
                $content = file_get_contents($this->file->getPath());
            }

            // convert encoding
            if ($this->options['convertEncoding']) {
                return mb_convert_encoding(
                    $content,
                    $this->options['toEncoding'],
                    $this->options['fromEncoding']
                );
            }

            return $content;
        }

        return false;
    }
}
