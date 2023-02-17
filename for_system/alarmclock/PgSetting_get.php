<?php
//連線資料庫
$pdo=new PDO("mysql:host=localhost;dbname=test_project;","root","");
$sql="SELECT * from projectkind";
$stmt=$pdo->prepare($sql);
$r=$stmt->execute();
$rows=$stmt->fetchAll();
echo json_encode($rows);
?>