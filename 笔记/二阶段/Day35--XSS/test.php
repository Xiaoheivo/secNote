<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="get">
        输入账号<input type="text" name="user" >
        输入密码<input type="text" name="pass" >
        <input type="submit" name='sub' value="提交">
    </form>
    <?php
        if(isset($_GET['sub'])){
            $user = $_GET['user'];
            $pass = $_GET['pass'];
            echo "<script>alert('user:$user,pass:$pass')</script>";
        }
    ?>
</body>
</html>