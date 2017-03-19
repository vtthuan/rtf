<?php

namespace AppBundle\Service;

class ConvertCsvToArray {
    
    public function __construct()
    {
    }
    
    public function convert($filename, $delimiter = ',') 
    {
        if(!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }
        
        $header = NULL;
        $data = array();
		ini_set('auto_detect_line_endings', true);
        if (($handle = fopen($filename, 'r')) !== FALSE) {			
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $row = array_map("utf8_encode", $row);
                if(!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

}