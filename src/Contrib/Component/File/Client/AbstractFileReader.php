<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\String\Encoding\Utf8;

/**
 * Abstract file reader.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class AbstractFileReader extends AbstractFileClient
{
    /**
     * Constructor.
     *
     * @param string $path    File path.
     * @param array  $options Options.
     */
    public function __construct($path, array $options = array())
    {
        parent::__construct($path, $options);

        ini_set('auto_detect_line_endings', $this->options['autoDetectLineEnding']);
    }

    // internal method

    /**
     * Create line handler object.
     *
     * @param resource $handle
     * @return \Contrib\Component\File\FileHandler\Plain\Iterator
     */
    abstract protected function createLineHandler($handle, $format = null, $type = null);

    /**
     * Initialize line reader.
     *
     * @param string $format File format.
     * @param string $type   Deserializing class name.
     * @return boolean true on success, false on failure.
     */
    protected function initReader($format = null, $type = null)
    {
        if (!isset($this->lineHandler)) {
            $handle = $this->file->openForRead();

            if ($handle === false) {
                return false;
            }

            $this->lineHandler = $this->createLineHandler($handle, $format, $type);
        }

        return true;
    }

    /**
     * Trim line.
     *
     * @param string $line
     * @return string
     */
    protected function trimLine($line)
    {
        return trim(Utf8::auto($line));
    }
}
