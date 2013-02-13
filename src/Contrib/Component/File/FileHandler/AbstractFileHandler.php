<?php
namespace Contrib\Component\File\FileHandler;

use Contrib\Component\File\SeekableFileInterface;
use Contrib\Component\File\File;

/**
 * Abstract File handler
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileHandler implements SeekableFileInterface
{
    /**
     * File.
     *
     * @var File
     */
    protected $file;

    /**
     * Options.
     *
     * * convert: boolean Default is false
     * * toEncoding: string Default is 'UTF-8'
     * * fromEncoding: string Default is 'auto'
     *
     * @var array
     */
    protected $options;

    /**
     * File handle.
     *
     * @var resource
     */
    protected $handle;

    /**
     * Constructor.
     *
     * @param File  $file    File.
     * @param array $options Options.
     */
    public function __construct(File $file, array $options = array())
    {
        $this->file    = $file;
        $this->options = $options + static::getDefaultOptions();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->close();
    }

    // API

    /**
     * Close file handle.
     *
     * @return boolean true on success, false on failure.
     */
    public function close()
    {
        if (isset($this->handle) && is_resource($this->handle)) {
            return fclose($this->handle);
        }

        return true;
    }

    // SeekableFileInterface

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\SeekableFileInterface::seek()
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (isset($this->handle) && is_resource($this->handle)) {
            return fseek($this->handle, $offset, $whence) === 0;
        }

        throw new \RuntimeException('File handle is not set.');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\SeekableFileInterface::getFile()
     */
    public function getFile()
    {
        return $this->file;
    }

    // accessor

    /**
     * Return options.
     *
     * @return array Options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return defualt options.
     *
     * @return array Default options.
     */
    public static function getDefaultOptions()
    {
        return array(
            'convert'      => false,
            'toEncoding'   => 'UTF-8',
            'fromEncoding' => 'auto',
        );
    }
}
