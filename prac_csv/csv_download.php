<?php
header('Content-Type: text/html; charset=utf-8');
include 'define.php';
include 'common.php';
include 'csv.php';
$result= array();
$where = '';
$checked = 'today';
$dbh = getDb();
$where = 'where csv_flag = ? and id between 1 and 5';
$sth = $dbh->prepare('select * from yotsuba05 ' . $where);
$sth->execute(array(0));
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
if(isset($_POST['submit'])){
	try{
		//csvダウンロード時csv_flag=1
		$where = 'where id between 1 and 5';
		$sth = $dbh->prepare('update yotsuba05 set csv_flag = ? ' . $where);
		$sth->execute(array(1));
	
		$csv = '';
		$downloadCsvDir = 'download_csv/';
		$filename = 'test' . date('Y-m-d-H-i-s') . '.csv';	
		$makeCsvFilename = $downloadCsvDir . $filename;
		//ファイル名にディレクトリを含めるとダウンロードされるときファイル名に変換される
		$csv = new Csv();
		$csv->setData($result);
		$csv->getCsvMs($makeCsvFilename);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $filename);
		$csv->getCsvMs('php://output');
		exit();
	}catch(PDOException $e){
		echo $e->getMessage();
	}
}
?>
<!doctype html>
<html lang='ja'>
<head>
<meta charset='utf-8'>
</head>
<body>
<div>
<form method='post' action=''>
<p>
	<input type='radio' name='today' value='today' id='today' <?php if($checked=='today') echo "checked='checked'" ?>><label for='today'>今日の受注を選択</label>
</p>
<div id='list'>
	<table cellpadding='0' cellspacing='0'>
		<?php if(count($result) > 0):?>
		<tr><th>商品名</th><th>価格</th></tr>
		<?php foreach($result as $row):?>
			<tr>
				<td><?php echo $row['product_name']	?></td>
				<td><?php echo $row['sale_price'] ?></td> 
			</tr>		
		<?php endforeach;?>
		<?php else: ?>
			<p>表示できるデータがありません</p>
		<?php endif; ?>
	</table>
</div>
<p><input type='submit' value='csvダウンロード' name='submit'  id='submit'></p>
</form>
</div>
</body>
</html>
