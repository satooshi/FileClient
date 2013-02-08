<?php
namespace Contrib\Component\File\FileHandler\Ltsv;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\File\FileType\Ltsv\Formatter;

/**
 * LTSV file line writer.
 */
class Writer extends LineWriter
{
    /**
     * LTSV Formatter object.
     *
     * @var Formatter
     */
    protected $formatter;

    /**
     * Constructor.
     *
     * @param resource $handle
     * @param string $newLine
     */
    public function __construct($handle, $newLine = PHP_EOL)
    {
        parent::__construct($handle, $newLine);

        $this->formatter = new Formatter();
    }

    /**
     * Write line to file (fwrite() function wrapper).
     *
     * @param array $line Line data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function write($line, $length = null)
    {
        if (is_array($line)) {
            return parent::write($this->formatter->formatItems($line), $length);
        }

        return parent::write($line, $length);
    }
}
