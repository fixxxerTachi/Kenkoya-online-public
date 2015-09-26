<?php
header('Content-Type: text/html; charset=UTF-8');
include 'define.php';
//文字化けしたらこれをチェックしてみる
//setlocale(LC_ALL, 'ja_JP.EUC-JP');
function getDb(){
	try{
		$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
		$user = DB_USER;
		$password = DB_PASSWORD;
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		); 
		return $dbh = new PDO($dsn, $user, $password, $options);
	}catch(Exception $e){
		$e->getMessage();
	}
}

function convertCsvToDb($filename){	
	$file = $filename;
	$data = file_get_contents($file);
	$data = mb_convert_encoding($data,'UTF-8','SJIS-win');
	$temp = tmpfile();
	fwrite($temp,$data);
	rewind($temp);
	while(($data = fgetcsv($temp,0,',')) !== FALSE){
		$csv[]=$data;
	}
	fclose($temp);
	return $csv;
}

function uploadCsv(){
	if(is_uploaded_file($_FILES['csvfile']['tmp_name'])){
		if(move_uploaded_file($_FILES['csvfile']['tmp_name'], 'uploaded_csv/' . $_FILES['csvfile']['name'])){
			echo 'ファイルをアップロードしました<br>';
			var_dump($_FILES);
		}else{
			echo 'ファイルをアップロードできませんでした<br>';
		}
	}else{
		echo 'ファイルが選択されていません<br>';
		exit();
	}
	return 'uploaded_csv/' . $_FILES['csvfile']['name'];
}


if(isset($_FILES['csvfile'])){
	$upload_file_name = uploadCsv();
	$csv = convertCsvToDb($upload_file_name);
	echo '<pre>';
	print_r($csv);
	echo '</pre>';

	try{
		$dbh = getDb();
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
		$error_arr=$stmt->errorInfo();
		var_dump($error_arr);
		echo 'データベースに登録しました';	
	}
	}catch(PDOEception $e){
	echo 'Connection failed: ' . $e->getMessage();
	$arr=$stmt->errorInfo();
	print_r($arr);
}
$dbh = null;
}
?>
<!doctype html>
<html lang='ja'>
<head>
<meta charset='utf-8'>
</head> <body>
<div>
	<form aciton='' method='post' enctype='multipart/form-data'>
	<dl>
		<dt>ファイルをアップロードしてください</dt>
		<dd><input type='file' name='csvfile'></dd>
		<dt></dt>
		<dd><input type='submit' value='upload'></dd>
	</dl>
	</form>
</div>
</body>
</html>
