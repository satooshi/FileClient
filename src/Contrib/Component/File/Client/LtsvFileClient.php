<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Ltsv\Reader;
use Contrib\Component\File\FileHandler\Ltsv\Writer;
use Contrib\Component\File\Formatter\LtsvFormatter;
use Contrib\Component\File\Parser\LtsvParser;

/**
 * LTSV file client.
 */
class LtsvFileClient extends FileClient
{
    /**
     * LtsvParser object.
     *
     * @var LtsvParser
     */
    protected $parser;

    /**
     * LtsvFormatter object.
     *
     * @var LtsvFormatter
     */
    protected $formatter;

    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function readContents()
    {
        $content = $this->read();

        if (!is_string($content)) {
            return false;
        }

        $lines = explode($this->newLine, $content);

        return $this->parseLines($lines);
    }

    /**
     * Return file content (fgets() function wrapper).
     *
     * @param integer $length Length to read.
     * @return array File content.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function readLines($length = null)
    {
        $lines = array();

        while (false !== $line = $this->readLine($length)) {
            $lines[] = $line;
        }

        return $lines;
    }

    /**
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param array $content Lines data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function writeContents($content)
    {
        $lines = $this->formatContents($content);

        if ($lines === false) {
            return false;
        }

        return $this->write($lines);
    }

    /**
     * Write lines to file (fgets() function wrapper).
     *
     * @param array   $lines  Lines data to write.
     * @param integer $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function writeLines(array $lines, $length = null)
    {
        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->writeLine($line, $length);
        }

        return $bytes;
    }

    /**
     * Append lines to file (file_put_contents() function wrapper).
     *
     * @param string $content Lines to append.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function appendContents($content)
    {
        $lines = $this->formatContents($content);

        if ($lines === false) {
            return false;
        }

        return $this->append($lines);
    }

    /**
     * Append lines to file (fgets() function wrapper).
     *
     * @param array  $lines  Lines data to append.
     * @param string $length Length to write.
     * @return integer Number of bytes written to the file.
     */
    public function appendLines(array $lines, $length = null)
    {
        $bytes = 0;

        foreach ($lines as $line) {
            $bytes += $this->appendLine($line, $length);
        }

        return $bytes;
    }

    // internal method

    /**
     * Parse LTSV lines.
     *
     * @param string $lines LTSV line.
     * @return array Parsed LTSV data.
     */
    protected function parseLines($lines)
    {
        if (!isset($this->parser)) {
            $this->parser = new LtsvParser();
        }

        $parsedLines = array();

        foreach ($lines as $i => $line) {
            $parsedLines[] = $this->parser->parseLine($line);
        }

        return $parsedLines;
    }

    /**
     * Format LTSV content.
     *
     * @param array $content LTSV content.
     * @return array Formatted LTSV lines.
     */
    protected function formatContents($content)
    {
        if (!is_array($content)) {
            return false;
        }

        if (!isset($this->formatter)) {
            $this->formatter = new LtsvFormatter();
        }

        $lines = array();

        foreach ($content as $label => $value) {
            $lines[] = $this->formatter->format($label, $value);
        }

        return $lines;
    }

    // create line handler

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createReader()
     */
    protected function createReader($handle)
    {
        return new Reader($handle);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\FileClient::createWriter()
     */
    protected function createWriter($handle)
    {
        return new Writer($handle, $this->newLine);
    }
}
