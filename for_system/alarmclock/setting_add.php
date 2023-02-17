<?php
//連線資料庫
require_once ("../db.php");
//---------------------------alarmclock資料表新增資料---------------------------
//INSERT 語法
$sql="Insert into settinglist(MonitorName) VALUES(:MonitorName);";
$result = $pdo->prepare($sql);

//執行 query
$result->execute([
	// 資料表欄位 => 前端傳值變數
	":MonitorName" => $_POST["MonitorName"],
]);

//结果返回json
if($result){
	echo json_encode(["msg"=>"新增成功"]);
}else{
	echo json_encode(["msg"=>"新增失敗"]);
}
?>