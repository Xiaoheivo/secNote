sql_connect.php(连接数据库的功能)



可以自己指定ip和端口



如果指定的是mysql服务的端口

- 密码正确的情况下,浏览器会很快加载完毕,并且无回显
- 密码错误则会提示Access denied for user 'root'@'localhost' (using password: YES)



如果指定的ip不存在

会在一段时间后报错:

![image-20220928142401975](SSRF%20Vulnerable%20Lab%E9%97%AF%E5%85%B3%E7%AC%94%E8%AE%B0.assets/image-20220928142401975.png)





如果指定其他端口,(ip存在,未开放)

- 指定端口未开放时,会在很短的时间内报错

  ![image-20220928141502242](SSRF%20Vulnerable%20Lab%E9%97%AF%E5%85%B3%E7%AC%94%E8%AE%B0.assets/image-20220928141502242.png)

- 如果指定的端口是开放端口,但不是MySQL服务的端口,浏览器会进入长时间的加载状态,实际上是在尝试和该端口"交流"





SMB:Windows内网的文件共享协议

可以用来探测有共享资源(开启了SMB)的主机