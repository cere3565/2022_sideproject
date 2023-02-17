<?php
//创建PDO
require_once ("../db.php");
//---------------------------alarmclock資料表更新資料---------------------------
$alarmclock_arr = array();
//將前端傳來的Json資料由字串轉為陣列
$alarmclock = json_decode($_POST["alarmclock"] , true);
$ID = $alarmclock['ID'];
$clockname = $alarmclock['ClockName'];
$Model = $alarmclock['Model'];

//陣列轉帶逗號字串
$MonitorList = $alarmclock['MonitorList'];
$InNotifierList = implode("," , $alarmclock['InNotifierList']);
$OutNotifierList = implode("," , $alarmclock['OutNotifierList']);
$alarmclock_Arr[]="('$ID','$clockname','$Model','$MonitorList','$InNotifierList','$OutNotifierList')";

//UPDATE語法
$sql="Replace into alarmclock values";
$sql .= implode(',', $alarmclock_Arr);
$stmt = $pdo->prepare($sql);
//執行 query
$result = $stmt->execute();

//---------------------------alarmitem資料表更新資料---------------------------

$MatchPJ = json_encode($alarmclock['MatchPJ']);
$NoMatch = json_encode($alarmclock['NoMatch']);

$sql="UPDATE alarmitem set MatchPJ = :MatchPJ , NoMatch = :NoMatch where ClockNo = :ClockNo";
$stmt = $pdo->prepare($sql);
//执行
$result = $stmt->execute([
	":MatchPJ" => $MatchPJ,
	":NoMatch" => $NoMatch,
	":ClockNo" => $ID
]);

//---------------------------clocklist資料表更新資料---------------------------
$clocklist_arr = array();
$clocklist = json_decode($_POST["clocklist"] , true);

for ($i = 0; $i < count($clocklist); $i++) {
	$CNO = $clocklist[$i]['CNO'];
	$ClockNo = $alarmclock['ID'];
	$AlarmNum = $clocklist[$i]['AlarmNum'];
	$IntervalTime = $clocklist[$i]['IntervalTime'];
	$clocklist_arr[] = "('$CNO','$ClockNo','$AlarmNum','$IntervalTime')";
}

//UPDATE語法
$sql = "Replace into clocklist values";
$sql .= implode(',', $clocklist_arr);
$stmt = $pdo->prepare($sql);
//執行 query
$result = $stmt->execute();


if($result){
	echo json_encode(["msg"=>"修改成功"]);
}else{
	echo json_encode(["msg"=>"修改失敗"]);
}
 
?>