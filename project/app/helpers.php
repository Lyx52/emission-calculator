<?php

use OpenSpout\Reader\CSV\Options as ReaderOptions;
use OpenSpout\Reader\CSV\Reader;

if (!function_exists('read_csv')) {
    function read_csv($fileName): Iterator {
        $options = new ReaderOptions();
        $reader = new Reader($options);
        $reader->open($fileName);
        foreach ($reader->getSheetIterator() as $sheet) {
            $header = [];
            $rows = [];
            foreach ($sheet->getRowIterator() as $row) {
                if (empty($header)) {
                    $header = $row->toArray();
                    continue;
                }
                yield array_combine($header, $row->toArray());
            }
        }
        $reader->close();
    }
}
