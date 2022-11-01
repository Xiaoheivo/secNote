<?php
   include "flag.php";

   $_403 = "Access Denied";

   $_200 = "Welcome Admin";

   //如果没有使用POST请求,直接200退出
   if ($_SERVER["REQUEST_METHOD"] != "POST")
   {
         die("BugsBunnyCTF is here :p…");
   }

   //如果请求表单中没有flag变量,直接403退出
   if ( !isset($_POST["flag"]) )
   {
         die($_403);
   }

   //遍历GET请求的键值对
   foreach ($_GET as $key => $value)
   {  
      // 获取到键值对后再将键值对换成变量
         $$key = $$value;
   }

   //遍历post请求的键值对
   foreach ($_POST as $key => $value)
   {
         $$key = $value;
   }

// 无效代码
   if ( $_POST["flag"] !== $flag )
   {
         die($_403);
   }


   echo "This is your flag : ". $flag . "\n";
   die($_200);
?>