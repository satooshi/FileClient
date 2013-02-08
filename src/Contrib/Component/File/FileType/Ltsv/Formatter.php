<?php
namespace Contrib\Component\File\FileType\Ltsv;

class Formatter
{
    /**
     * Format data.
     *
     * @param string $label Label
     * @param string $value Value
     * @return string Formatted LTSV line.
     * @throws \RuntimeException
     */
    public function format($label, $value)
    {
        if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return sprintf('%s:%s', $label, $value);
        }

        throw new \RuntimeException(sprintf('Could not format LTSV value of label:%s.', $label));
    }

    /**
     * Format LTSV items to line.
     *
     * @param array $items LTSV items.
     * @return string Formatted line.
     */
    public function formatItems(array $items)
    {
        $fields = array();

        foreach ($items as $label => $value) {
            $fields[] = $this->format($label, $value);
        }

        return implode("\t", $fields);
    }
}
