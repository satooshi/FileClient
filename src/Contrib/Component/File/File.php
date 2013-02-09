<?php
namespace Contrib\Component\File;

class File
{
    protected $path;
    protected $throwException;

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
            $handle = fopen($this->path, 'r');

            if (false === $handle && $this->throwException) {
                throw new \RuntimeException("Failed to read file for read : $this->path.");
            }

            return $handle;
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
            $handle = fopen($this->path, 'w');

            if (false === $handle && $this->throwException) {
                throw new \RuntimeException("Failed to open file for write : $this->path.");
            }

            return $handle;
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
            $handle = fopen($this->path, 'a');

            if (false === $handle && $this->throwException) {
                throw new \RuntimeException("Failed to open file for append : $this->path.");
            }

            return $handle;
        }

        return false;
    }
}
