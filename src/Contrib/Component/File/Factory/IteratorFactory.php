<?php
namespace Contrib\Component\File\Factory;

use Contrib\Component\File\FileHandler\Plain\LineIterator;
use Contrib\Component\File\Client\AbstractFileIterator;
use Contrib\Component\File\Client\Plain\FileReaderIterator;
use Contrib\Component\File\Client\Plain\FileReaderLimitIterator;

class IteratorFactory
{
    // line iterator

    public function createLineIterator($path, array $options = array())
    {
        $lineHandler = $this->createLineReader($path, $options);

        return new LineIterator($lineHandler);
    }

    public function createGenericLineIterator($path, $format, $type = null, array $options = array())
    {
        $lineHandler = $this->createGenericLineReader($path, $format, $type, $options);

        return new LineIterator($lineHandler);
    }

    // file reader iterator

    public function createFileReaderIterator($path, array $options = array())
    {
        $lineIterator = $this->createLineIterator($path, $options);

        return $this->createFileReaderIteratorWith($lineIterator, $options);
    }

    public function createGenericFileReaderIterator($path, $format, $type = null, array $options = array())
    {
        $lineIterator = $this->createGenericLineIterator($path, $format, $type = null, $options);

        return $this->createFileReaderIteratorWith($lineIterator, $options);
    }

    protected function createFileReaderIteratorWith($lineIterator, array $options = array())
    {
        $options = $this->configureFileReaderIteratorOptions($options);

        $skipEmptyCount = $options['skipEmptyCount'];
        $limit          = $options['limit'];
        $offset         = $options['offset'];

        if ($limit > 0 && !$skipEmptyCount) {
            $iterator = new \LimitIterator($lineIterator, $offset, $limit);
        } else {
            $iterator = $lineIterator;
        }

        if ($skipEmptyCount && $limit > 0) {
            return new FileReaderLimitIterator($iterator, $options);
        }

        return new FileReaderIterator($iterator, $options);
    }

    // configuration

    protected function getFileReaderIteratorDefaultOptions(array $options = array())
    {
        $default = AbstractFileIterator::getDefaultOptions();
        $additional = array(
            'skipEmptyCount' => true,
            'limit'          => 0,
            'offset'         => 0,
        );

        return $options + $default + $additional;
    }

    protected function configureFileReaderIteratorOptions(array $options = array())
    {
        $default = $this->getFileReaderIteratorDefaultOptions($options);

        $default['limit']  = $this->filterLimit($default['limit']);
        $default['offset'] = $this->filterOffset($default['offset']);

        return $default;
    }

    // internal method

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
}
