<?php
  require_once ("../db.php");
  $sql="SELECT * from users;";
  $stmt=$pdo->prepare($sql);
  //執行
  $result=$stmt->execute([$id]);
  //獲取資料
  $rows = $stmt->fetchAll();
  //轉為JSON輸出
  echo json_encode($rows);
?>