<?php
	$account = $_GET['user'];
	// echo $yesterday;
	//連線資料庫
	require_once ("../db.php");

	//通過前端發出之使用者資訊抓取是否有漏通知(notifyDay=null)或今日新增要通知的專案，
	//以便登入時於前台發出通知(容錯機制與log紀錄)
	$sql="SELECT nNO ,PNo ,NotifierPerson ,notifyday ,
							 PJName, TimeKind, Deadline
				  from notifierlistday, project 
				 where NotifierPerson = :account and notifyDay = '0000-00-00 00:00:00' and PJNO = PNo;";
	$stmt = $pdo->prepare($sql);
	//執行
	$result = $stmt->execute([":account" => $_GET['user']]);
	//獲取資料
	$rows = $stmt->fetchAll();

	echo json_encode($rows);
?>