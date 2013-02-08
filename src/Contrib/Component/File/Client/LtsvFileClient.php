<?php
namespace Contrib\Component\File\Client;

use Contrib\Component\File\FileHandler\Ltsv\Reader;
use Contrib\Component\File\FileHandler\Ltsv\Writer;
use Contrib\Component\File\FileType\Ltsv\Formatter;
use Contrib\Component\File\FileType\Ltsv\Parser;

/**
 * LTSV file client.
 */
class LtsvFileClient extends FileClient
{
    /**
     * LTSV Parser object.
     *
     * @var Parser
     */
    protected $parser;

    /**
     * LTSV Formatter object.
     *
     * @var Formatter
     */
    protected $formatter;

    // API

    /**
     * Return file content (file_get_contents() function wrapper).
     *
     * @return array File contents.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function read()
    {
        $content = parent::read();

        if (!is_string($content)) {
            return false;
        }

        $lines = explode($this->newLine, $content);

        return $this->parseLines($lines);
    }

    /**
     * Write lines to file (fule_put_contents() function wrapper).
     *
     * @param array $content Lines data to write.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function write($content)
    {
        $lines = $this->formatContents($content);

        if ($lines === false) {
            return false;
        }

        return parent::write($lines);
    }

    /**
     * Append lines to file (file_put_contents() function wrapper).
     *
     * @param string $content Lines to append.
     * @return integer Number of bytes written to the file.
     * @throws \RuntimeException Throws on failure if $throwException is set to true.
     */
    public function append($content)
    {
        $lines = $this->formatContents($content);

        if ($lines === false) {
            return false;
        }

        return parent::append($lines);
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
            $this->parser = new Parser();
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
            $this->formatter = new Formatter();
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
