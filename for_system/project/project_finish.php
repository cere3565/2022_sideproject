<?php
	//連線資料庫
	require_once ("../db.php");

	//更新已通知的專案以做通知紀錄(容錯機制與log紀錄)
	$sql = "UPDATE pjclockmenu set NotifierYN = :yno where PID = :ProjectID;";
	$result = $pdo->prepare($sql);
	//執行 query
	$result->execute([
    ":yno" => "YES",
		":ProjectID" => $_POST["ProjectID"]
	]);

	if($result){
		echo json_encode(["msg"=>"修改成功"]);
	}else{
		echo json_encode(["msg"=>"修改失敗"]);
	}
?>