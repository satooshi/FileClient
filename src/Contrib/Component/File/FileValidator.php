<?php
namespace Contrib\Component\File;

class FileValidator
{
    // API

    /**
     * Return whether the file is readable.
     *
     * @param string  $path           File path.
     * @param boolean $throwException Whether to throw exception.
     * @return boolean true if the file is readable, false otherwise and $throwException is set to true.
     * @throws \RuntimeException Throws if the file is not readable and $throwException is set to true.
     */
    public static function canRead($path, $throwException = true)
    {
        $message = static::validateReadable($path);

        if ($message) {
            if ($throwException) {
                throw new \RuntimeException($message);
            }

            return false;
        }

        return true;
    }

    /**
     * Return whether the file is writable.
     *
     * @param string  $path           File path.
     * @param boolean $throwException Whether to throw exception.
     * @return boolean true if the file is writable, false otherwise and $throwException is set to true.
     * @throws \RuntimeException Throws if the file is not writable and $throwException is set to true.
     */
    public static function canWrite($path, $throwException = true)
    {
        $message = static::validateWritable($path);

        if ($message) {
            if ($throwException) {
                throw new \RuntimeException($message);
            }

            return false;
        }

        return true;
    }

    // internal method

    /**
     * Validate the file is readable.
     *
     * @param string $path File path.
     * @return string Validation message if the file is not readable, empty string otherwise.
     */
    protected static function validateReadable($path)
    {
        if (!file_exists($path)) {
            return "File is not found : $path.";
        }

        if (!is_file($path)) {
            return "Path is not a file : $path.";
        }

        if (!is_readable($path)) {
            return "File is not readable : $path.";
        }

        return '';
    }

    /**
     * Validate the file is writable.
     *
     * @param string $path File path.
     * @return string Validation message if the file is not writable, empty string otherwise.
     */
    protected static function validateWritable($path)
    {
        if (!file_exists($path)) {
            $dir = dirname($path);

            if (!file_exists($dir)) {
                return "Directory to be written is not found : $dir.";
            }

            if (!is_writable($dir)) {
                return "File and its directory are not writable : $path, $dir.";
            }
        } else {
            if (!is_file($path)) {
                return "Path is not a file : $path.";
            }

            if (!is_writable($path)) {
                return "File is not writable : $path.";
            }
        }

        return '';
    }
}
