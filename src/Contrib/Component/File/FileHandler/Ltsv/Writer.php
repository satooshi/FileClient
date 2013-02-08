<?php
namespace Contrib\Component\File\FileHandler\Ltsv;

use Contrib\Component\File\FileHandler\Plain\Writer as LineWriter;
use Contrib\Component\File\Formatter\LtsvFormatter;

/**
 * LTSV file line writer.
 */
class Writer extends LineWriter
{
    /**
     * LtsvFormatter object.
     *
     * @var LtsvFormatter
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

        $this->formatter = new LtsvFormatter();
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
            return parent::write($this->asLtsvLine($line), $length);
        }

        return parent::write($line, $length);
    }

    /**
     * Dump LTSV line.
     *
     * @param array $data
     * @return string
     */
    public function asLtsvLine(array $data)
    {
        $content = array();

        foreach ($data as $key => $value) {
            $content[] = $this->formatter->format($label, $value);
        }

        return implode("\t", $content);
    }
}
