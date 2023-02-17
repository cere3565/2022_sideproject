<?php
	$rTime = date('Y/m/d H:i:s');
	print($rTime);
	$nowdt = time();
	echo($nowdt . "<br>");
	echo(date("Y-m-d H:i:s",$nowdt));

	$notifierlist = json_decode($_POST["notifyList"] , true);
	$notifyNo = $notifierlist[0]["nNO"];
	print_r("<pre>");print_r($notifierlist);
	//連線資料庫
	require_once ("../db.php");

	//更新已通知的專案以做通知紀錄(容錯機制與log紀錄)
	$sql = "UPDATE notifierlistday set notifyDay = NOW() where nNO = :notifyNo;";
	$result = $pdo->prepare($sql);
	//執行 query
	$result->execute([
		":notifyNo" => $notifyNo
	]);

	if($result){
		echo json_encode(["msg"=>"修改成功"]);
	}else{
		echo json_encode(["msg"=>"修改失敗"]);
	}
?>