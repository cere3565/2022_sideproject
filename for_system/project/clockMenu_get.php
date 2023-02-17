<?php
  //連線資料庫
  require_once ("../db.php");
  $ProjectInfo_arr = array();
  //將前端傳來的Json資料由字串轉為陣列
  $ProjectInfo = json_decode($_POST["ProjectInfo"] , true);

  $sql = "SELECT ID, ClockName, MonitorList, InNotifierList, OutNotifierList,
                 MatchPJ, MatchAttr, NoMatch
            from alarmclock
       LEFT JOIN alarmitem ON alarmclock.ID = alarmitem.ClockNo
           where alarmclock.MonitorList = :TimeKind
        group by alarmclock.ClockName;";
  $stmt = $pdo->prepare($sql);
  //執行
  $result = $stmt->execute([
    ":TimeKind" => $ProjectInfo['TimeKind']
  ]);
  //獲取資料
  $rows = $stmt->fetchAll();

  $CutRowsNoM = array();
  $svalue = $ProjectInfo["PJKind"] . ";" . $ProjectInfo["Attr"];

  $NOMat = array();
  for($i = 0;$i < count($rows);$i++){
    $NM = 0;
    $CutRowsNoM = json_decode($rows[$i]["NoMatch"] , true);
    if (in_array($svalue,$CutRowsNoM)) {
      $NM++;
    }
    $NOMat[$i] = $NM; 
  }

  for($i = 0;$i < count($NOMat);$i++){
    if($NOMat[$i] == "1"){
      while ($rows[$i]) {
        unset($rows[$i]);
        break;
      }
    }  
  }
  //調整陣列順序
  $rows = array_values($rows);
  $Match = array();
  $cutAttr = explode('.',$ProjectInfo["Attr"]);

  for($i = 0;$i < count($rows);$i++) {
    $M = 0;
    $CutRowsMat = json_decode($rows[$i]["MatchPJ"] , true);
    for($j = 0;$j < count($CutRowsMat);$j++) {
      $cutMatchPJ = explode(';',$CutRowsMat[$j]);
      $MatchPJ = $cutMatchPJ[0];  //專案種類
      $MatchAttr = $cutMatchPJ[1]; //專案形式
      if ($MatchPJ == $ProjectInfo["PJKind"]) {
        $M++;
        $MatchAttr = explode('.',$MatchAttr);
        
        if($MatchAttr[0] == "*" or $MatchAttr[0] == $cutAttr[0]){
          $M++;
        }
        if($MatchAttr[1] == "*" or $MatchAttr[1] == $cutAttr[1]){
          $M++;
        }
        if($MatchAttr[2] == "*" or $MatchAttr[2] == $cutAttr[2]){
          $M++;
        }
      }
    }
    $Match[$i] = $M;        
  }
  
  for($i = 0;$i < count($rows);$i++){
    if($Match[$i] < "4"){
      while ($rows[$i]) {
        unset($rows[$i]);
        break;
      }
    }  
  }
  $rows = array_values($rows);
  
  //轉為JSON輸出
  echo json_encode($rows);
?>
