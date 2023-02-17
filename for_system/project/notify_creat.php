<?php
$today = date('Y-m-d');// echo $today;
$InNotify_arr = array();
$Notifylist_arr = array();

//連線資料庫
require_once ("../db.php");

//創建明天要發出通知人員之清單
$sql="SELECT project.ProjectID ,project.Deadline ,alarmclock.InNotifierList
     	  from project, pjclockmenu, alarmclock
       where project.Deadline <= '{$today}' and project.ProjectID = pjclockmenu.PID and pjclockmenu.alarmNo = alarmclock.ID;";
//執行
$stmt = $pdo->prepare($sql);
$result = $stmt->execute();
//獲取資料
$PJ_rows = $stmt->fetchAll();

if (count($PJ_rows) > 1){
	for($i = 0; $i < count($PJ_rows); $i++){
		$InNotify_arr[$i] = explode(",", $PJ_rows[$i]['InNotifierList']);
	}
}
else{ $InNotify_arr = explode(",", $PJ_rows[0]['InNotifierList']); }


//抓取要通知的部門人員

for($i = 0; $i < count($InNotify_arr); $i++){
	$sql="SELECT * from users	where";
	$sqlstr = "";
	for($j = 0;$j < count($InNotify_arr[$i]); $j++)
	{
		if($j == 0){
			$sqlstr = $InNotify_arr[$i][$j];
			$sql .= " department = '$sqlstr'";
		}
		else{
			$sqlstr = $InNotify_arr[$i][$j];
			$sql .= " or department = '$sqlstr'";
		}
	}
	//執行
	$stmt = $pdo->prepare($sql);
	$result = $stmt->execute();
	//獲取資料
	$deprows = $stmt->fetchAll();
}


$todayTime = date('Y/m/d H:i:s');
for($i = 0; $i < count($PJ_rows); $i++){
	for($j = 0; $j < count($deprows); $j++){
		$PNo = $PJ_rows[$i]['ProjectID'];
		$NotifierPerson = $deprows[$j]['User'];
		$NotifierDept = $deprows[$j]['department'];

		$seasql="SELECT * from notifierlistday where PNo = :PNo and NotifierPerson = :NotifierPerson and NotifierDept = :NotifierDept;";
		$stmt = $pdo->prepare($seasql);
		//執行
		$result = $stmt->execute([
			":PNo" => $PNo,
			":NotifierPerson" => $NotifierPerson,
			":NotifierDept" => $NotifierDept,
		]);
		//獲取資料
		$searows = $stmt->fetchAll();

		if(count($searows) == 0){
			$Notifylist_arr[]= "('$PNo','$NotifierPerson','$NotifierDept',NOW())";
		}		
	}
	$seasql = null;
	$stmt = null;
	// $stmt = null;
}
print_r("<pre>");print_r($Notifylist_arr);

if(count($Notifylist_arr)>0){
	$sql = "Insert into notifierlistday(PNo, NotifierPerson, NotifierDept, creatDay) values";
	$sql .= implode(',', $Notifylist_arr);
	$result = $pdo->prepare($sql);
	//執行 query
	$result->execute();
}


// 结果返回json
if($result){
	echo json_encode(["msg"=>"新增成功"]);
}else{
	echo json_encode(["msg"=>"新增失敗"]);
}
?>