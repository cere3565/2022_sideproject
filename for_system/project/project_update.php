<?php
//创建PDO
require_once ("../db.php");
//---------------------------project資料表更新資料---------------------------

$sql="UPDATE project set alarmNo = :alarmNo , alarmName = :alarmName where ProjectID = :ClockNo";
//预处理  生成stmt对象
$stmt = $pdo->prepare($sql);
//执行
$result = $stmt->execute([
	":alarmNo" => $_POST["ClockNo"],
	":alarmName" => $_POST["alarmName"],
	":ClockNo" => $_POST["ClockNo"]
]);


if($result){
	echo json_encode(["msg"=>"修改成功"]);
}else{
	echo json_encode(["msg"=>"修改失敗"]);
}
 
?>