# 正则表达式

## 用法

1.准备一个被查找的字符串

```js
var str='<a href="https://paper.seebug.org/" target="_blank"></a>';
```

2.定义正则表达式模式(规则)的

```js
var pattern = /https:\/\/.*\//i;
```



3.查找匹配位置,匹配内容,替换

 1. 查找匹配位置:

    ```
    .serch(patt)
    ```

	2. 获取匹配的内容:

    ```
    .match()[0]
    ```

3. 替换

   ```
   .replace(pattern,string)
   ```

   

