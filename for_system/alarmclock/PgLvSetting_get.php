<?php
$stid = $_GET['id'];
//連線資料庫
$pdo=new PDO("mysql:host=localhost;dbname=test_project;","root","");
$sql="SELECT stdata from setting WHERE stid = ?";
$stmt=$pdo->prepare($sql);
$result=$stmt->execute([$stid]);
$rows=$stmt->fetchAll();
echo json_encode($rows);
?>