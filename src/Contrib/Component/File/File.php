<?php
namespace Contrib\Component\File;

/**
 * File handle.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class File
{
    /**
     * File path.
     *
     * @var string
     */
    protected $path;

    /**
     * Whether to throw exception.
     *
     * @var boolean
     */
    protected $throwException;

    /**
     * Constructor.
     *
     * @param string  $path           File path.
     * @param boolean $throwException Whether to throw exception.
     */
    public function __construct($path, $throwException = true)
    {
        $this->path = $path;
        $this->throwException = $throwException;
    }

    /**
     * Return whether the file is readable.
     *
     * @return boolean true if the file is readable.
     * @throws \RuntimeException Throw if the file is not readable and $throwException is set to true.
     */
    public function isReadable()
    {
        return FileValidator::canRead($this->path, $this->throwException);
    }

    /**
     * Return whether the file is writable.
     *
     * @return boolean true if the file is writable.
     * @throws \RuntimeException Throw if the file is not writable and $throwException is set to true.
     */
    public function isWritable()
    {
        return FileValidator::canWrite($this->path, $this->throwException);
    }

    /**
     * Open file for read.
     *
     * @return resource|boolean File handle on success, false on faiiure
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForRead()
    {
        if ($this->isReadable()) {
            return fopen($this->path, 'r');
        }

        return false;
    }

    /**
     * Open file for write.
     *
     * @return resource|boolean File handle on success, false on faiiure
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForWrite()
    {
        if ($this->isWritable()) {
            return fopen($this->path, 'w');
        }

        return false;
    }

    /**
     * Open file for append
     *
     * @return resource|boolean File handle on success, false on faiiure.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function openForAppend()
    {
        if ($this->isWritable()) {
            return fopen($this->path, 'a');
        }

        return false;
    }

    // accessor

    /**
     * Return file path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return whether to throw exception.
     *
     * @return boolean
     */
    public function throwException()
    {
        return $this->throwException;
    }
}
