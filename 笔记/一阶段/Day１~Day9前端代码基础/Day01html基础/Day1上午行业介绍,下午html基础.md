



# 亮哥电话

![image-20220726091548361](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726091548361.png)



# 正式上课第一天

## 各阶段内容讲解

CIA：完整性、机密性、可用性

国内：CIA+可审查性、不可抵赖性





# 【学习目标】

[toc]



1. HTML简介

2. HTML网页基本结构

3. 网页的基本信息

4. HTML规范

   1. ```html
      <!DOCTYPE html>
      <html>
      
      <head>
      	<meta charset="utf-8"
      	<title>页面标题</title>
      </head>
      
      <body>
      	<h1>标题</h1>
      	<p>段落</p>
      	<br/>换行
      	<strong>加粗</strong>  <em>斜体</em>
          大于号`&gt;`&gt;      小于号`&lt;`&lt;     引号`&quot;` &quot;  版权符号`&copy;` &copy;     空格`&nbsp;` 
          图片<img src="">
          超链接<a href="">文本描述</a>
          无序列表
          <ul>
          	<li></li>
      	    <li></li>
          </ul>
          
          有序列表
          <ol>
              <li></li>
              <li></li>
          </ol>
      </body>
      
      </html>
      ```

5. 标题标签  H1--H6

6. 段落标签  `<p></p>`

7. 换行标签  `<br/>`

8. 水平线标签 `<hr/>`

9. 字体标签  `<strong>加粗</strong>  <em>斜体</em>`<strong>加粗</strong>  <em>斜体</em>

10. 注释和特殊符号   大于号`&gt;`&gt;      小于号`&lt;`&lt;     引号`&quot;` &quot;  版权符号`&copy;` &copy;     空格`&nbsp;` 

11. 图片标签   `<img>`

12. 超链接标签 `<a href></a>`

13. 列表标签 

    1. 无序列表`<ul> <li>内容</li> </ul>`
    2. 有序列表`<ol> <li>内容</li> </ol>`



# 随堂操作

html:编写网页的语言，叫做超文本标记语言，最新版本是5

### 第一个html文档

```html
<html>
    <head>
        <title>1st page</title>
    </head>
    <body>
        <h1>
            一级标题
        </h1>
        <p>
            第一行
        </p>
        <p>
            第二行
        </p>
    </body>
</html>
```



### ＨＴＭＬ网页基本结构



![image-20220726150200097](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726150200097.png)









```html
< !DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content = "text/html;charset=gb2312"/>
    <title>标题</title>
</head>
    <body>
        
    </body>
    </html>
```







### HTML编写规范

1. HTML标签必须关闭
2. 属性值必须用引号括起来
3. 标签必须正确嵌套
4. 必须添加文档类型声明<!DOCTYPE html>





### 常用标签

#### 标题标签

主要用于文章标题

数字越大,字体越小

```html
<h1></h1>
<h2></h2>
<h3></h3>
<h4></h4>
<h5></h5>
<h6></h6>
```





```html
<!DCOTYPE html>
<html>

    <head>
        <meta charset = "utf-8"/>
        <title>标题</title>
    </head>
    <body>
        <h1>一级标题</h1>
    </body>

</html>
```

##### 如图

![image-20220726170435177](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726170435177.png)







#### 段落标签

主要用于文章的段落自动换行

` <p></p>`

```html
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>标题</title>
</head>

<body>
	<h1>一级标题</h1>
	<p>sajdiasjd</p>
	<p>djsadji</p>
	第三行<br/>
	第四行<br/>

	<h2>北京欢迎你</h2>
	<hr/>
	<p>萨达萨达萨达萨达是的撒的萨达萨达是DNAUI斧蛤互粉敬爱的沙发上的等哈手机号打金快</p>
</body>

</html>
```



##### 如图

![](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726154111876.png)



#### 换行标签

主要用于网页中的换行

` <br/>`

```html
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>标题</title>
</head>

<body>
	<h1>一级标题</h1>
	<p>sajdiasjd</p>
	<p>djsadji</p>
	第三行<br/>
	第四行<br/>

	<h2>北京欢迎你</h2>
	<hr/>
	<p>萨达萨达萨达萨达是的撒的萨达萨达是DNAUI斧蛤互粉敬爱的沙发上的等哈手机号打金快</p>
</body>

</html>
```



##### 如图

![image-20220726154124254](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726154124254.png)



#### 水平线标签

在网页中添加一条水平线

` <hr/> `

```html
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>标题</title>
</head>

<body>
	<h1>一级标题</h1>
	<p>sajdiasjd</p>
	<p>djsadji</p>
	第三行<br/>
	第四行<br/>

	<h2>北京欢迎你</h2>
	<hr/>
	<p>萨达萨达萨达萨达是的撒的萨达萨达是DNAUI斧蛤互粉敬爱的沙发上的等哈手机号打金快</p>
</body>

</html>
```

##### 如图

![image-20220726154134961](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726154134961.png)



##### 小练习

完成以下效果

![image-20220726154640940](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726154640940.png)

```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title>清平乐</title>
	</head>

	<body>
		<h1>清平乐</h1>
		<hr/>
		年年雪里，常插梅花醉，挼尽梅花无好<br/>
		意，赢得满衣清泪！<br/>
		今年海角天涯，萧萧两鬓生华。<br/>
		看取晚来风势，故应难看梅花。<br/>
	</body>
    
</html>
```



#### 字体样式标签

```html
加粗:<strong>...</strong>
斜体:<em>...</em>
```

完成以下效果

![image-20220726161522602](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726161522602.png)



```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title>清平乐</title>
	</head>

	<body>
		<h1>清平乐</h1>
	<hr/>

		<strong>年年雪里，常插梅花醉，挼尽梅花无好</strong><br/>
		<em>意，赢得满衣清泪！</em><br/>
		<strong>今年海角天涯，萧萧两鬓生华。</strong><br/>
		<em>看取晚来风势，故应难看梅花。</em><br/>
	</body>
</html>
```



#### 注释和特殊符号

| 特殊符号       | 替换字符 | 实例         |
| -------------- | -------- | ------------ |
| 空格           | `&nbsp;` |              |
| 大于号(>)      | `&gt;`   |              |
| 小于号(<)      | `&lt;`   |              |
| 引号(")        | `&quot`  | 引号需要两个 |
| 版权符号&copy; | `&copy;` |              |



![image-20220726162804572](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726162804572.png)



```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title>清平乐</title>
	</head>

	<body>
		<h1>清平乐</h1>
		<p>四个    空格</p>
		<script>alert("哦豁!!!");</script>
	</body>
</html>

```

![image-20220726162831708](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726162831708.png)

![image-20220726162845997](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726162845997.png)

只显示一个空格,script行不显示



![image-20220726163210103](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726163210103.png)



使用`&nbsp;`表示空格,使用`&gt;`表示大于号>   使用`&ly;`表示小于号

```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title>清平乐</title>
	</head>

	<body>
		<h1>清平乐</h1>
		<p>四个&nbsp;&nbsp;&nbsp;&nbsp;空格</p>
		&lt;script&gt;alert("哦豁!!!");&lt;/script&gt;
	</body>
</html>

```

![image-20220726163434880](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726163434880.png)

空格变成四个,下面一行也正常显示

##### 小练习

实现以下效果

![image-20220726165135425](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726165135425.png)



```html
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8">
		<title>清平乐</title>
	</head>

	<body>
		<h1>人物简介</h1><br/>

		<strong>李清照</strong>
		（<em>1084年3月13日~1155年5月12<br/>
		日</em>），宋代女词人，号易安居士，婉约<br/>
		词派代表，有“千古第一才女”之称。<br/>
		早期生活优裕，金兵入据中原时，流寓<br/>
		南方，境遇孤苦。所作词，前期多写其<br/>
		悠闲生活，后期多悲叹身世，情调伤<br/>
		感。形式上善用白描手法，自辟途径，<br/>
		语言清丽。论词强调协律，崇尚典雅，<br/>
		提出词“别是一家”之说，反对对作诗<br/>
		文之法作词，留有诗集《易安居士文<br/>
		集》、《易安词》等。<br/>	
		<hr/>
		&copy;2013&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;国信安版权所有
	</body>
</html>

```



完成图

![image-20220726170021086](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726170021086.png)





# 亮哥发的笔记



![image-20220726182756464](C:\Users\HE\AppData\Roaming\Typora\typora-user-images\image-20220726182756464.png)







# 预习

- HTML图片标签
- HTML超链接标签
- HTML列表标签
  - HTML有序列表
  - HTML无序列表

参考链接:https://www.runoob.com/html/html-tutorial.html





## HTML图片标签

图片用`<img>`标签表示   其中`<img>`标签内可以添加多重属性,且`<img>`标签没有闭合标签

### HTML图像的src属性和alt属性:

#### src:

src:指图片的url地址,可以是在线路径也可以是本地路径

`<img src="图片的url地址">`

#### alt:

alt:对图片的描述,给图片添加alt属性,可以在图片无法加载时告诉用户服务端对此图片的描述

`<img src="图片的url地址" alt="填图片的描述">`

### HTML图像的width属性和height属性

#### width(宽度)   height(高度),两者的默认单位为像素(px)

`<img src="图片的url地址" alt="填图片的描述" width="宽度" height="高度">`

## HTML超链接标签

HTML使用`<a></a>`来表示超链接,需要注意的是,a标签是一个标签对,需要写闭合标签



### HTML链接语法--href属性

`<a href="url">文本<a/>`

通过href属性来设置超链接的url以访问资源,文本则用来描述这个超链接,用户直接看到并点击的就是文本的内容

### HTML链接语法--ID属性

给超链接插入id,值为tips

`<a id="tips">有用的提示部分</a>`

再创建一个新链接链接到`id="tips"的链接

` <a href = "#tips">访问'有用的提示部分'</a>`这样就可以通过这个链接访问到id设置为tips的资源

创建一个链接,访问指定url的指定id资源

`<a href="http://xiaohe.com/index.html#tips">`访问xiaohe.com的主页中id为tips的资源



## HTML列表标签

HTML支持有序列表、无序列表、定义列表,今天学习了解有序和无序列表

### HTML无序列表

html用` <ul> </ul>`标签表示无序列表,ul标签是成对出现的,需要写闭合标签

```html
<ul>
    <li>COffee</li>
    <li>Milk</li>
</ul>
```

![image-20220726194805638](C:\Users\HE\Desktop\笔记\一阶段\Day１~Day10前端代码基础\Day1上午行业介绍,下午html基础.assets\image-20220726194805638.png )





### HTML有序列表

html用` <ol></ol>`标签表示有序列表,ol标签同样是成对出现的,需要写闭合标签

```html
<ol>
	<li>coffee</li>
    <li>Milk</li>
</ol>
```

![image-20220726195201094](C:\Users\HE\Desktop\笔记\一阶段\Day１~Day10前端代码基础\Day1上午行业介绍,下午html基础.assets\image-20220726195201094.png)

### HTML自定义列表

```html
<dl>
    <dt>列表头1</dt>
    <dd>列表1内容1</dd>
    <dd>列表1内容2</dd>
    <dd>列表1内容3</dd>
    <dt>列表头2</dt>
    <dd>列表2内容1</dd>
    <dd>列表2内容2</dd>
    <dd>列表2内容3</dd>
</dl>
```

![image-20220727145237735](C:\Users\HE\Desktop\笔记\一阶段\Day１~Day10前端代码基础\Day1上午行业介绍,下午html基础.assets\image-20220727145237735.png)



