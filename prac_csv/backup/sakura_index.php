<?php
//これをチェックしてみる
//setlocale(LC_ALL, 'ja_JP.EUC-JP');
$file = 'yotsuba05.csv';
$data = file_get_contents($file);
$data = mb_convert_encoding($data,'UTF-8','SJIS-win');
$temp = tmpfile();
fwrite($temp,$data);
rewind($temp);
while(($data = fgetcsv($temp,0,',')) !== FALSE){
	$csv[]=$data;
}
fclose($temp);
echo '<pre>';
print_r($csv);
echo '</pre>';

//Db
$dsn = 'mysql:host=mysql493.db.sakura.ne.jp;dbname=akatome_prac_csv';
$user = 'akatome';
$password = 'akatome2014';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
); 
/*
try{
	$dbh = new PDO($dsn, $user, $password);
	var_dump($dbh);
	foreach($dbh->query('select * from yotsuba05') as $row){
		print_r($row);
	}
	$dbh=null;
	
}catch(PDOEception $e){
	echo 'Connection failed: ' . $e->getMessage();
}
*/
/*
$stmt = $dbh->prepare('insert into yotsuba05(code,product_code,sale_price) values(:code,:product_code,:sale_price)');

data=array(
	array(1,2,3),
	array(4,5,6),
);
foreach($data as $row){
	$stmt->bindValue(':code',$row[0]);
	$stmt->bindValue(':product_code',$row[1]);
	$stmt->bindValue(':sale_price',$row[2]);
	$stmt->execute();
}
*/
try{
		$dbh = new PDO($dsn, $user, $password, $options);

		$stmt = $dbh->prepare('insert into yotsuba05(
			product_code,
			code,
			maker,
			product_name,
			size,
			cost_price,
			sale_price,
			profit,
			profit_ratio,
			freshness_date,
			additive,
			allergen,
			calorie,
			note
		) values(
			:product_code,
			:code,
			:maker,
			:product_name,
			:size,
			:cost_price,
			:sale_price,
			:profit,
			:profit_ratio,
			:freshness_date,
			:additive,
			:allergen,
			:calorie,
			:note
		)');


	foreach($csv as $row){
		$stmt->bindValue(':code',$row[0]);
		$stmt->bindValue(':product_code',$row[1]);
		$stmt->bindValue(':maker',$row[2]);
		$stmt->bindValue(':product_name',$row[3]);
		$stmt->bindValue(':size',$row[4]);
		$stmt->bindValue(':cost_price',$row[5]);
		$stmt->bindValue(':sale_price',$row[6]);
		$stmt->bindValue(':profit',$row[7]);
		$stmt->bindValue(':profit_ratio',$row[8]);
		$stmt->bindValue(':freshness_date',$row[9]);
		$stmt->bindValue(':additive',$row[10]);
		$stmt->bindValue(':allergen',$row[11]);
		$stmt->bindValue(':calorie',$row[12]);
		$stmt->bindValue(':note',$row[13]);
		$stmt->execute();
		$arr=$stmt->errorInfo();
		print_r($arr);
	}
	$dbh=null;
}catch(PDOEception $e){
	echo 'Connection failed: ' . $e->getMessage();
	$arr=$stmt->errorInfo();
	print_r($arr);
}
