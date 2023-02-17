<?php
$id=$_GET['ID'];
//連線資料庫
$pdo=new PDO("mysql:host=localhost;dbname=test_project;","root","");
//sql語法
$sql="SELECT * from clocklist WHERE ClockNo = ? ;";
$stmt=$pdo->prepare($sql);
//執行
$result=$stmt->execute([$id]);
//獲取資料
$rows=$stmt->fetchAll();
//轉為JSON輸出
echo json_encode($rows);
?>