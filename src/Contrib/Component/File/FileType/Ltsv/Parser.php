<?php
namespace Contrib\Component\File\FileType\Ltsv;

class Parser
{
    /**
     * Parse LTSV line.
     *
     * @param string $line LTSV line.
     * @return array LTSV items.
     * @throws \RuntimeException Throw on parse error.
     */
    public function parseLine($line)
    {
        $tsvFields = explode("\t", trim($line));

        $items = array();

        foreach ($tsvFields as $tsvField) {
            list($label, $value) = $this->parseField($tsvField);

            if ($item !== null) {
                $items[$label] = $value;
            }
        }

        return $items;
    }

    /**
     * Parse LTSV field.
     *
     * @param string $tsvField LTSV field.
     * @return array LTSV item.
     * @throws \RuntimeException Throw on parse error.
     */
    public function parseField($tsvField)
    {
        if (false !== stripos($tsvField, ':')) {
            $labelValue = explode(':', $tsvField, 2);

            if (count($labelValue) === 2) {
                return $labelValue;
            }
        }

        throw new \RuntimeException(sprintf('Could not parse LTSV field(%s).', $tsvField));
    }
}
