<?php
namespace Contrib\Component\File\FileHandler;

use Contrib\Component\File\File;

/**
 * Abstract File handler
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileHandler
{
    /**
     * File.
     *
     * @var File
     */
    protected $file;

    /**
     * Encoding options.
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
     * @param array $options Encoding options.
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

    /**
     * Seek on a file pointer.
     *
     * @param integer $offset
     * @param string  $whence
     * @return integer 0 on success, -1 on failure
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if (isset($this->handle) && is_resource($this->handle)) {
            return fseek($this->handle, $offset, $whence);
        }

        throw new \RuntimeException('File handle is not set.');
    }

    // accessor

    /**
     * Return file.
     *
     * @return \Contrib\Component\File\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return encoding options.
     *
     * @return array Encoding options.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return defualt encoding options.
     *
     * @return array Default encoding options.
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
