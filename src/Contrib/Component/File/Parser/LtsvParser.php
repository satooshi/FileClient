<?php
namespace Contrib\Component\File\Parser;

class LtsvParser
{
    /**
     * Parse LTSV line.
     *
     * @param string $line
     * @return array
     */
    public function parseLine($line)
    {
        $tsvFields = explode("\t", trim($line));

        $items = array();

        foreach ($tsvFields as $tsvField) {
            $item = $this->parseField($tsvField);

            if ($item !== null) {
                $items[$item[0]] = $item[1];
            }
        }

        return $items;
    }

    /**
     * Parse LTSV field.
     *
     * @param string $tsvField
     * @return array|null
     */
    public function parseField($tsvField)
    {
        if (false !== stripos($tsvField, ':')) {
            $labelValue = explode(':', $tsvField);

            if (count($labelValue) === 2) {
                return $labelValue;
            }
        }

        return null;
    }
}
