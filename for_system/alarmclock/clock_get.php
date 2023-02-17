<?php
//連線資料庫
$pdo=new PDO("mysql:host=localhost;dbname=test_project;","root","");
//定义sql语句
$sql="SELECT alarmclock.ID,alarmclock.ClockName,alarmclock.Model,alarmclock.MonitorList,
      alarmclock.InNotifierList,alarmclock.OutNotifierList,
      GROUP_CONCAT(DISTINCT alarmitem.MatchPJ) as MatchPJ,
      GROUP_CONCAT(DISTINCT alarmitem.MatchAttr) as MatchAttr,
      GROUP_CONCAT(DISTINCT alarmitem.NoMatch) as NoMatch
      from alarmclock 
      LEFT JOIN alarmitem ON alarmclock.ID = alarmitem.ClockNo 
      GROUP by alarmclock.ID;";
//预处理  创建stmt对象
$stmt = $pdo->prepare($sql);
//执行操作
$r = $stmt->execute();
//获取数据
$rows=$stmt->fetchAll();
//转为json数据输出
echo json_encode($rows);
// print_r($rows);
?>