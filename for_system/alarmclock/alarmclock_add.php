<?php
//連線資料庫
require_once ("../db.php");
//---------------------------alarmclock資料表新增資料---------------------------
$alarmclock_arr = array();
//將前端傳來的Json資料由字串轉為陣列
$alarmclock = json_decode($_POST["alarmclock"] , true);
$clockname = $alarmclock['ClockName'];
$Model = $alarmclock['Model'];
//陣列轉帶逗號字串
if (is_array($alarmclock['MonitorList'])){ $MonitorList = implode("," , $alarmclock['MonitorList']);}
else{	$MonitorList = $alarmclock['MonitorList']; }

if (is_array($alarmclock['InNotifierList'])) { $InNotifierList = implode("," , $alarmclock['InNotifierList']); }
else { $InNotifierList = $alarmclock['InNotifierList']; }

if (is_array($alarmclock['OutNotifierList'])){ $OutNotifierList = implode("," , $alarmclock['OutNotifierList']); }
else{ $OutNotifierList = $alarmclock['OutNotifierList']; }

$alarmclock_Arr[]="('$clockname','$Model','$MonitorList','$InNotifierList','$OutNotifierList')";

//INSERT 語法
$sql="Insert into alarmclock(ClockName,Model,MonitorList,InNotifierList,OutNotifierList) values";
$sql .= implode(',', $alarmclock_Arr);
$result = $pdo->prepare($sql);
//執行 query
$result->execute();
$ClockNo = $pdo->lastInsertId();

//---------------------------alarmitem資料表新增資料---------------------------
$alarmitem_arr = array();
print_r(json_encode($alarmclock['MatchCond']));
$MatchPJ = json_encode($alarmclock['MatchPJ']);
$NoMatch = json_encode($alarmclock['NoMatch']);
$alarmitem_arr[] = "('$ClockNo','$MatchPJ','$MatchAttr','$NoMatch')";


$sql = "Insert into alarmitem(ClockNo,MatchPJ,MatchAttr,NoMatch) values";
$sql .= implode(',', $alarmitem_arr);
$result = $pdo->prepare($sql);
//執行 query
$result->execute();

//---------------------------clocklist資料表新增資料---------------------------
$clocklist_arr = array();
$clocklist = json_decode($_POST["clocklist"] , true);
// print_r($clocklist);

for ($i = 0; $i < count($clocklist); $i++) {
	$AlarmNum = $clocklist[$i]['AlarmNum'];
	$IntervalTime = $clocklist[$i]['IntervalTime'];
	$clocklist_arr[] = "('$ClockNo','$AlarmNum','$IntervalTime')";
}
$sql = "Insert into clocklist(ClockNo, AlarmNum, IntervalTime) values";
$sql .= implode(',', $clocklist_arr);
$result = $pdo->prepare($sql);
//執行 query
$result->execute();


// 结果返回json
if($result){
	echo json_encode(["msg"=>"新增成功"]);
}else{
	echo json_encode(["msg"=>"新增失敗"]);
}

?>