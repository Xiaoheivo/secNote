问题：
xml文档结构包含哪几部分？

三部分

xml实体长什么样子？





文档元素中,子元素可以多次使用吗



```xml
<?xml version="1.0"?>

<!DOCTYPE note [
<!ELEMENT note(to,from,head,body)>
<!ELEMENT to(#PCDATA)>
<!ELEMENT from(#PCDATA)>
<!ELEMENT head(#PCDATA)>
<!ELEMENT body(#PCDATA)>
]>

<note>
	<to>afei</to>
    <from>xiaohe</from>
    <head>message</head>
    <body>nnsshxs</body>
</note>
```

