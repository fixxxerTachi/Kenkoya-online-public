<?php
class Csv
{
	public $data;
    function setData($data) {
        $this->data = $data;
    }

    function getCsv($filename,$is_ms = false) {
        $fp = fopen($filename, "w");
        if (true === $is_ms) {
            stream_filter_register("msLineEnding", "ms_line_ending_filter");
            stream_filter_append($fp, "msLineEnding");
        }
        foreach ($this->data as $line) {
            if (true === $is_ms) {
                mb_convert_variables('cp932', 'utf-8', $line);
            }
            fputcsv($fp, $line);
        }
        fclose($fp);
    }

    function getCsvMs($filename) {
        $this->getCsv($filename,true);
    }
}

class ms_line_ending_filter extends php_user_filter
{
    function filter($in, $out, &$consumed, $closing) {
        while ($bucket = stream_bucket_make_writeable($in)) {
            $bucket->data = preg_replace("/\n$/", "", $bucket->data);
            $bucket->data = preg_replace("/\r$/", "", $bucket->data);
            $bucket->data = $bucket->data . "\r\n";
            $consumed += $bucket->datalen;
            stream_bucket_append($out, $bucket);
        }
        return PSFS_PASS_ON;
    }
}