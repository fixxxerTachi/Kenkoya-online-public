<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include 'define.php';
include 'common.php';
include 'csv.php';
$result= array();
$checked='today';
$where = '';
if(isset($_POST['reload'])){
var_dump($result);
	$start = $_POST['start'];
	$end = $_POST['end'];
	$where = "where csv_flag = ? and id between 1 and 5";
	$dbh = getDb();
	$sth = $dbh->prepare('select * from yotsuba05 '. $where);
	$sth->execute(array(0));
	$result = $sth->fetchAll(PDO::FETCH_ASSOC);
	$_SESSION['result'] = $result;
}

if(isset($_POST['submit'])){
	//csv_flagの更新
	$id_list = array();
	$result = $_SESSION['result'];
	foreach($result as $key => $value){
		$id_list[] = (int)$value['id'];
	}
	$idlist_to_string = implode(',',$id_list);
	$where = ' where id in (' . $idlist_to_string . ')';
	
	$dbh = getDb();
	$sth = $dbh->prepare('update yotsuba05 set csv_flag = ? ' . $where);
	$sth->execute(array(1));
	
	$_SESSION['result'] = array();
	session_destroy();
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
}
?>
<!doctype html>
<html lang='ja'>
<head>
<meta charset='utf-8'>
<link href="jquery-ui/jquery-ui.css" rel="stylesheet">
<script src="jquery-ui/external/jquery/jquery.js"></script>
<script src="jquery-ui/jquery-ui.js"></script>

</head>
<body>
<div>
<form method='post' action=''>
<p>
	<input type='radio' name='select_day' value='select_day' id='select_day' <?php if($checked=='today') echo "checked='checked'" ?>><label for='today'>今日の受注を選択</label>
</p>
<p><input type='text' name='start'  id='start'> ~ <input type='text' name='end' id='end'></p>
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
		<?php else:?>
			<p>日付を選択して下さい</p>
		<?php endif; ?>
	</table>
</div>
<p><input type='submit' value='更新'  name='reload' id='reload'></p>
<p><input type='submit' value='csvダウンロード' name='submit'  id='submit'></p>
</form>
</div>
<script>
$('#start').datepicker();
$('#end').datepicker();
</script>
</body>
</html>
