//admin_order説明表示
$(function(){
	var recieved_message =
		"「受付済みにする」にチェックが入っているものを更新します。\nそうすることでお客様からのオーダー変更や、キャンセルができなくなります。\n状態が発注待ちになります。";
	var order_message = 
		"「発注済みにする」にチェックが入っているデータをダウンロードします。\n状態が出荷待ちになります。";
	var items_message = 
		"画面に表示されているリストをダウンロードします。";
	var shipped_message = 
		"「出荷済みにする」にチェックが入っているものを配送済みにします。\nクレジットカート決済は売上が確定します。";
		
//受付登録
	$("#recieved_desc").on("click",function(){
		alert(recieved_message);
	});
	$("#order_desc").on("click",function(){
		alert(order_message);
	});
	$("#items_desc").on("click",function(){
		alert(items_message);
	});
	$("#shipped_desc").on("click",function(){
		alert(shipped_message);
	});
});
