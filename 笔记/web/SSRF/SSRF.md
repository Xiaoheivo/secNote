# SSRF

[toc]

## 概念

SERVER SIDE REQUEST FORGERY(服务器请求伪造)的简写。

服务器原本提供资源获取相关的服务，而资源的地址由用户可控。



## 危害

条件满足的情况下：

突破网络防护边界，造成内部信息或资源被外部攻击者获取，比如探测内网存活主机、获取信任身份，攻击内网服务等

## 原理

利用服务器能够发送网络请求的特点，攻击者控制了服务器指定的发送内容

## 支持的请求协议

```
http://		探测内网主机存活、端口开放情况
gopher://	发送get或post请求：用TCP包攻击内网应用，如Fast CGI、Redis
dict://		泄露安装软件版本信息，查看端口，操作内网redis访问等
file:///		读取本地文件
```

## 相关PHP函数和类

- file_get_contents():将整个文件或者一个url所指向的文件内容读入一个字符串中
- readfile():读取一个文件的内容并输出
- fsockopen():发开一个网络连接或者一个Unix套接字连接
- curl_exec():初始化一个新的会话,返回一个curl句柄,共curlsetopt(),curlexec()和curlclose()函数使用

## 挖掘方式和挖掘场景

服务器调用第三方资源时,其资源URL前端可控,比如:

1. 找特征URL:通过URL地址分享网页内容,如:?url、src、from、img、u、link、share、wap、urllink、source、target、3g、display、sourceURL、imageURL、domain、res、resource、file、host
2. 找特殊场景：
   1. 转码服务
   2. 在线翻译
   3. 图片加载与下载：通过url地址加载或者下载图片
   4. 图片、文章收藏功能
   5. 未公开的api实现以及其他调用url的功能
   6. 文件下载
   7. 连接其他网络服务（mysql连接测试等）
3. 源码审计特殊函数

## 漏洞利用的条件

受害服务器可通内网,或者,受害服务器接收前端自定义资源url并解析

## SSRF绕过

1. 通过url解析绕过只解析@后面的域名,例如:http://www.baidu.com@127.0.0.1

2. 通过dns解析绕过私有地址限制探测内网,例如:www.127.0.0.1.sslip.io(dns服务器会将这个地址解析成127.0.0.1,更多内网地址解析绕过替换127.0.0.1即可)

3. 短链接转换绕过:https://my5353.com/此网站可生成内网地址的短连接,但是是https协议,直接改成http://+短连接访问即可

4. 编码进制绕过,比如:

   127.0.0.1

   利用八进制IP地址绕过:0177.0.0.1

   利用16进制IP绕过:0x7f000001

   利用十进制IP地址绕过:2130706433

   对所有IP都适用,浏览器搜索ip进制转换即可在线转换

5. 各种指向127.0.0.1的地址:

   http://localhost/

   http://0/		#0在Windows下代表0.0.0.0,而在Linux下代表127.0.0.1(只在Linux适用)

   http://[0:0:0:0:0:ffff:127.0.0.1]		#仅Linux可用

   http://[::]:80/		#仅Linux可用

   http://127。0 。0 。1		#中文句号绕过

   

6. 利用不存在的协议头绕过指定的协议头

   file_get_contents()函数的一个特性，即当PHP的 file_get_contents()函数在遇到不认识的协议头时候会将这个协议头当做文件夹，造成目录穿越漏洞，这时候只需不断往上跳转目录即可读到根目录的文件。(include(函数也有类似的特性)

   将协议头写成不存在的协议头:httpssssss://

   file_get_contents()读到不认识的协议头,就会当做文件夹,然后配合目录穿越

   ssrf.php?url=httpsss://../../../../../../etc/passwd

   ssrf.php?url=httpss://ss../.././../etc/passwd

   此方法可在SSRF的众多协议被禁止且只能使用它规定的某些协议的情况下来进行读取文件

7. 猜测白名单规则及其弱点

## ipc$

Windows提供给管理员管理电脑的

> http://t.zoukankan.com/LittleHann-p-6907308.html
>
> https://www.cnblogs.com/lzkalislw/p/15657634.html

## 防范思路

宏观上:

1. 编写正确的白名单策略
2. 对未设计访问内网的服务器,禁止其进行内网资源读取
3. 使其与内网隔离(可以禁止服务器主动向内网发起请求,但不影响内网其他设备向服务器发起请求)

## gopher协议生成TCP请求

### 通过gopher协议发起TCP请求(含http请求)

1. 构造HTTP数据包(可以抓包获取),只需要获取请求行和Host字段

2. 将获取到的内容中`?  空格   & 回车换行`四种特殊字符进行url编码

   ? -->%3f		空格 -->%20   & -->%26 	回车换行(\r\n) --> %0d%0a

3. 前面附加gopher部分内容:gopher://host:port/资源路径/_

4. 发送gopher协议



gopher发起HTTP GET请求

```
GET /get.php?name=xiaohe HTTP/1.1
Host: 192.168.1.1

将上面提到的地方替换即可

gopher://192.168.1.1:80/_GET%20/get.php%26name=xiaohe%20HTTP/1.1%0d%0aHost:%20192.168.1.1%0d%0a
```

gopher发起HTTP POST请求

```
POST /post.php HTTP/1.1
Content-Type: application/ x-www-form-urlencoded
Content-Length: 11		//需要注意不同内容length的计算
Connection: close		//可以没有,如果没有,连接结束会延迟一段时间

name=xiaohe

同样,将特殊字符url编码即可
```



