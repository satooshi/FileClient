<?php
namespace Contrib\Component\File\Client\Plain;

use Contrib\Component\File\Client\AbstractFileReader;
use Contrib\Component\File\FileHandler\Plain\Iterator as LineIterator;
use Contrib\Component\File\FileHandler\AbstractFileHandler;

/**
 * File reader iterator.
 *
 * options:
 *
 * * newLine: string New line to be written (Default is PHP_EOL).
 * * throwException: boolean Whether to throw exception.
 * * autoDetectLineEnding: boolean Whether to use auto_detect_line_endings.
 * * skipEmptyCount: Whether to skip count on empty line.
 * * limit: integer Count of the limit.
 * * offset: integer Offset of the limit.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class FileReaderIterator extends AbstractFileReader
{
    /**
     * Whether the client suspended to read file.
     *
     * @var boolean
     */
    protected $isSuspended = false;

    /**
     * Constructor.
     *
     * @param string $path    File path.
     * @param array  $options Options.
     */
    public function __construct($path, array $options = array())
    {
        $options = $options + array(
            'skipEmptyCount' => true,
            'limit'          => 0,
            'offset'         => 0,
        );

        parent::__construct($path, $options);
    }

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
        $limit          = $this->filterLimit($this->options['limit']);
        $offset         = $this->filterOffset($this->options['offset']);
        $skipEmptyCount = $this->options['skipEmptyCount'];

        $iterator = $this->createLineIterator($skipEmptyCount, $limit, $offset);

        if ($iterator === false) {
            return false;
        }

        if ($skipEmptyCount && $limit > 0) {
            $this->iterateLimit($callback, $iterator, $limit);
        } else {
            $this->iterate($callback, $iterator);
        }

        if ($iterator instanceof \IteratorIterator) {
            $this->isSuspended = $iterator->getInnerIterator()->valid();
        } elseif ($iterator instanceof \Iterator) {
            $this->isSuspended = $iterator->valid();
        }

        return $iterator;
    }

    // internal method

    /**
     * Create LineIterator.
     *
     * @param boolean $skipEmpty Whether to skip count if empty line.
     * @param integer $limit     Count of the limit.
     * @param integer $offset    Offset of the limit.
     * @return \Iterator LimitIterator if limit specified, LineIterator otherwise.
     * @throws \RuntimeException Throw on failure if $throwException is set to true.
     */
    protected function createLineIterator($skipEmptyCount, $limit = 0, $offset = 0)
    {
        if (!$this->initReader()) {
            return false;
        }

        if ($limit <= 0 || $skipEmptyCount) {
            return $this->lineHandler;
        }

        return new \LimitIterator($this->lineHandler, $offset, $limit);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\File\Client\AbstractFileReader::createLineReader()
     */
    protected function createLineReader($handle)
    {
        return new LineIterator($handle);
    }

    /**
     * Iterate iterator.
     *
     * @param callable $callback
     * @param \Iterator $iterator
     * @return void
     */
    protected function iterate($callback, \Iterator $iterator)
    {
        foreach ($iterator as $numLine => $data) {
            $line = $this->trimLine($data);

            if (empty($line)) {
                // skip empty line
                continue;
            }

            $items = $this->filterIteratedLine($line);

            if (false === $callback($items, $numLine)) {
                return;
            }
        }
    }

    /**
     * Iterate iterator until limit excluding empty line count.
     *
     * @param callable  $callback
     * @param \Iterator $iterator
     * @param integer   $limit
     * @return void
     */
    protected function iterateLimit($callback, \Iterator $iterator, $limit)
    {
        $readLine = 0;

        foreach ($iterator as $numLine => $data) {
            $line = $this->trimLine($data);

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

    // config

    /**
     * Filter iterated line in walk().
     *
     * @param string $line
     * @return mixed
     */
    protected function filterIteratedLine($line)
    {
        return $line;
    }

    /**
     * Filter optional limit.
     *
     * @param integer $limit Count of the limit.
     * @return integer
     */
    protected function filterLimit($limit)
    {
        if (is_numeric($limit)) {
            $limit = (int)$limit;
        }

        if (!is_int($limit)) {
            $limit = 0;
        }

        return $limit;
    }

    /**
     * Filter optional offset.
     *
     * @param integer $offset Offset of the limit.
     * @return integer
     */
    protected function filterOffset($offset)
    {
        if (is_numeric($offset)) {
            $offset = (int)$offset;
        }

        if (!is_int($offset)) {
            $offset = 0;
        }

        return $offset;
    }

    // accessor

    /**
     * Return whether the client suspended to read file.
     *
     * @return boolean
     */
    public function isSuspended()
    {
        return $this->isSuspended;
    }

    /**
     * Set line handler.
     *
     * @param AbstractFileHandler $lineHandler
     * @return void
     */
    public function setLineHandler(AbstractFileHandler $lineHandler)
    {
        $this->lineHandler = $lineHandler;
    }
}
