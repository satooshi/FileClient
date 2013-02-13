<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileIterator;

/**
 * File reader iterator.
 *
 * options:
 *
 * * newLine: string New line to be written (Default is PHP_EOL).
 * * throwException: boolean Whether to throw exception.
 * * autoDetectLineEnding: boolean Whether to use auto_detect_line_endings.
 * * limit: integer Count of the limit.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderLimitIterator extends AbstractFileIterator
{
    // API

    /**
     * Apply a callback to every line except for empty line.
     *
     * @param callable $callback function ($line, $numLine).
     * @return \Iterator|false Iterator on success, false on failure.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    public function walk($callback)
    {
        if (!$this->isReadable()) {
            return false;
        }

        $this->iterate($callback, $this->options['limit']);
        $this->initSuspended();

        return $this->lineHandler;
    }

    // internal method

    /**
     * Iterate iterator until limit excluding empty line count.
     *
     * @param callable  $callback
     * @param \Iterator $iterator
     * @param integer   $limit
     * @return void
     */
    protected function iterate($callback, $limit)
    {
        $readLine = 0;

        foreach ($this->lineHandler as $numLine => $line) {
            if (is_string($line)) {
                $line = $this->trimLine($line);
            }

            if (empty($line)) {
                // skip empty line
                continue;
            }

            $items = $this->filterIteratedLine($line);

            if (false === $callback($items, $numLine)) {
                return;
            }

            $readLine++;

            if ($readLine === $limit) {
                return;
            }
        }
    }
}
