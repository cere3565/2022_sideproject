<?php
mb_internal_encoding("utf-8");
$to="cere1727@gmail.com";
$subject=mb_encode_mimeheader("信件主旨","utf-8");
$message="測試內容";
$headers="MIME-Version: 1.0\r\n";
$headers.="Content-type: text/html; charset=utf-8\r\n";
$headers.="From:".mb_encode_mimeheader("寄件者","utf-8")."<winola@winolatw.com>\r\n";
if(mail("$to", "$subject", "$message", "$headers")):
  echo "信件已經發送成功。";//寄信成功就會顯示的提示訊息
else:
  echo "信件發送失敗！";//寄信失敗顯示的錯誤訊息
endif;
?>