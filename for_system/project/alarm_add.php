<?php
//連線資料庫
require_once ("../db.php");
//---------------------------alarmclock資料表新增資料---------------------------
$clockMenu_arr = array();
//將前端傳來的Json資料由字串轉為陣列
$selected = json_decode($_POST["selected"] , true);
print_r("<pre>");print_r($selected);

for ($i = 0; $i < count($selected); $i++) {
  $PID = $_POST["ClockNo"];
	$alarmNo = $selected[$i]['ID'];
	$alarmName = $selected[$i]['ClockName'];
	$clockMenu_arr[] = "('$PID','$alarmNo','$alarmName')";
}
$sql = "Insert into pjclockmenu(PID, alarmNo, alarmName) values";
$sql .= implode(',', $clockMenu_arr);
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