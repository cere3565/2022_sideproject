<?php
  require_once ("../db.php");

  $sql="SELECT * from users where User = :username and Password = :userpws;";
  $stmt = $pdo->prepare($sql);
  //執行
  $result = $stmt->execute([
    ":username" => $_GET['username'],
	  ":userpws" => $_GET['userpws']
  ]);
  //獲取資料
  $rows = $stmt->fetchAll();
  echo json_encode($rows);
?>