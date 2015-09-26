<?php
$data= array("hoge,fuga","hoge\nhuga");
$csv='';
$filename='test.csv';
foreach($data as $key => $value){
	$value = str_replace(',','","',$value);
	$value = str_replace('\n',chr(10),$value);
	$csv .= $value . ',';
	$csv .= "\n";
}
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $filename);
echo mb_convert_encoding($csv,'SJIS','UTF-8');
