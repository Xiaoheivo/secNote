问题：
xml文档结构包含哪几部分？

三部分

xml实体长什么样子？





文档元素中,子元素可以多次使用吗



```xml
<?xml version="1.0"?><!-- 声明一个xml文档 -->   
声明必须放在第一行最前面(只要写了xml声明,就必须放到第一行最前并且顶格,否则报错)

xml文档中有任何内容没有打标签都会报错
文本内容必须注释,注释不是为了让他不显示,而是让文本不起xml的作用


声明DTD也就是模板,
模板的根元素类型为note
note有四个子元素:to from head body
子元素的类型为:#PCDATA  即会被解析器解析的文本。这些文本将被解析器检查实体以及标记。
<!DOCTYPE note [
<!ELEMENT note(to,from,head,body)>
<!ELEMENT to(#PCDATA)>
<!ELEMENT from(#PCDATA)>
<!ELEMENT head(#PCDATA)>
<!ELEMENT body(#PCDATA)>
]>

利用模板建立note元素,其中包含四个子元素
<note>
	<to>afei</to>
    <from>xiaohe</from>
    <head>message</head>
    <body>nnsshxs</body>
</note>
```

