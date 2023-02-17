<?php
$dbhost = 'localhost'; //一般是 localhost或127.0.0.1
$dbuser = 'root'; //一般是 root
$dbpasswd = '';
$dbname = 'test_project';
$dbcharacter = 'utf8'; //一般是 utf8
try
{
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname};charset={$dbcharacter}", $dbuser, $dbpasswd);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的模擬效果
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //讓資料庫顯示錯誤原因
    // echo "連線成功";
} catch (PDOException $e) {
    die("無法連上資料庫：" . $e->getMessage());
}
?>