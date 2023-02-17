<?php
//创建pdo对象
$pdo=new PDO("mysql:host=localhost;dbname=test_project;","root","");
//定义sql语句
$sql="SELECT MonitorName from settinglist;";
//预处理  创建stmt对象
$stmt=$pdo->prepare($sql);
//执行操作
$r=$stmt->execute();
//获取数据
$rows=$stmt->fetchAll();
//转为json数据输出
echo json_encode($rows);
?>