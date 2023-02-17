<?php
$dNO=$_GET['id'];
require_once ("../db.php");

$sql="delete from clocklist where CNO=?";
$stmt=$pdo->prepare($sql);
//執行
$r=$stmt->execute([$dNO]);
if($r){
	echo json_encode(["msg"=>"删除成功"]);
}else{
	echo json_encode(["msg"=>"删除失败"]);
}
?>