<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<form action="" method="post">
请输入要请求的资源的URL <input type="text" name="url" id="" placeholder="可以是任意协议"></br>
请输入请求的端口<input type="text" name="port" id="" placeholder="如果不指定，则默认80端口">如果url中包含端口，可不填此项,</br>
请输入请求方式  <input type="text" name="method" id="" placeholder="GET|POST不区分大小写">不指定请求方式则默认为GET</br>
<input type="submit" name="sub" value="提交">
</form>
<?php

if(isset($_REQUEST['url'])){
    $url = $_REQUEST['url'];            //url
    echo "后端接收到的url:  $url</br></br>";
    if($_REQUEST['method']!=""){
        $method = strtoupper($_REQUEST['method']);      //请求方式
    }
    $method = "GET";
    //url处理,删除原url的任何协议
    strpos($url,":///")?$url = substr($url,strpos($url,":///")+4):$url;
    strpos($url,"://")?$url = substr($url,strpos($url,"://")+3):$url;
    // $url = str_ireplace("http://","",$url);
    // $url = str_ireplace("https://","",$url);

    //获取域名
    $host = substr($url,0,strpos($url,"/"));

    //获取路径
    $path = substr($url,strpos($url,"/")+1);
    if($host == ""){
        $host =$url;
        $path = "";
    }
    echo "处理后的url:  $url";
    //生成gopher请求
    if(strpos($url,":")){   //url自带端口
        $Host = substr($host,0,strpos($host,":"));  //去掉host中的端口号，放到请求头的Host字段中
        //url版
        $gopher="gopher://$host/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$Host%0d%0a";
        
        echo "</br></br>转换后的url</br></br>$gopher";
        echo "</br></br>再次url编码,直接放到url参数中访问：</br></br>".urlencode($gopher);

        echo "</br></br>带curl命令, 可直接复制到curl中执行:</br></br>";
        //curl版
        $curlGopher = "curl gopher://$host/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$Host%0d%0a";
        echo $curlGopher;
    }elseif($_REQUEST['port']!=""){  //用户指定端口
        $port = $_REQUEST['port'];     
        

        //url版
        $gopher="gopher://$host:$port/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$host%0d%0a";
        echo "</br></br>转换后的url</br></br>$gopher";
        echo "</br></br>再次url编码,直接放到url参数中访问：</br></br>".urlencode($gopher);

        echo "</br></br>带curl命令, 可直接复制到curl中执行:</br></br>";
        //curl版
        $curlGopher = "curl gopher://$host:$port/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$host%0d%0a";
        echo $curlGopher;
    }else{          //无端口
        $port = "80";      

        //url版
        $gopher="gopher://$host:$port/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$host%0d%0a";
        echo "</br></br>转换后的url</br></br>$gopher";
        echo "</br></br>再次url编码,直接放到url参数中访问：</br></br>".urlencode($gopher);

        echo "</br></br>带curl命令, 可直接复制到curl中执行:</br></br>";
        //curl版
        $curlGopher = "curl gopher://$host:$port/_$method%20/$path%20HTTP/1.1%0d%0aHost:%20$host%0d%0a";
        echo $curlGopher;
    }

}

