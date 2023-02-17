<?php
$cID=$_GET['ID'];
require_once ("../db.php");

$sql="delete from alarmclock where ID=?";
$stmt=$pdo->prepare($sql);
//執行 query
$result=$stmt->execute([$cID]);

$sql="delete from alarmitem where ClockNo=?";
$stmt=$pdo->prepare($sql);
//執行 query
$result=$stmt->execute([$cID]);

$sql="delete from clocklist where ClockNo=?";
$stmt=$pdo->prepare($sql);
//執行 query
$result=$stmt->execute([$cID]);

if($result){
	echo json_encode(["msg"=>"删除成功"]);
}else{
	echo json_encode(["msg"=>"删除失敗"]);
}
?>