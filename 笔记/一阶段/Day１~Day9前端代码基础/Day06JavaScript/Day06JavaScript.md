[toc]

## 1.什么是javascript?有何作用?

​	一种前端编程脚本语言,简称js,能够管理浏览器页面内容(可见与不可见)



## 2.js和JSP有何区别?

​	js:一种前端编程语言

​	JSP:一种后端编程语言,其他语言类似的还有:php,asp



## 3.js是否能够管理本地计算机的文件?

​	不能,    js的能力受到浏览器沙盒虚拟机的限制,不能直接管理,需要人类明确授权



## 4.第一个js代码

```html
<h1>第一个javascript代码</h1>
<p id="demo">这是一个段落</p>
<p id="demo2">这是第二个段落</p>
<input type="button" onclick="displayDate()" value="显示日期(byId)">
<button id='btn1' type="button" onclick="displayDateByClass()">显示日期(byCLassName)</button>

<script>
    function displayDate(){
        document.getElementById("demo").innerHTML = Date();
    }

    function displayDateByClass(){
        document.getElementById("demo2").innerHTML = Date();
    }
</script>


```





## 5.javascript的四种引入方式

### 	1.内部方式

​	就是在网页任何位置,加入`<script>js代码</script>`

​	023.html:

```html
<h1>内部方式引入js代码</h1>
<p id="demo">这是一个段落</p>
<p id="demo2">这是第二个段落</p>
<input type="button" onclick="displayDate()" value="显示日期(byId)">
<button id='btn1' type="button" onclick="displayDateByClass()">显示日期(byCLassName)</button>

<script>
    function displayDate(){
        document.getElementById("demo").innerHTML = Date();
    }

    function displayDateByClass(){
        document.getElementById("demo2").innerHTML = Date();
    }
</script>
```

### 	2.外部方式

​	需要创建一个.js后缀的文件来存放js文件然后通过`<script src="js文件路径">		</script>`的方式引入

​	023.html:

```html
<head>
	<script src="./js/023.js"></script>
</head>

<h1>外部方式引入js</h1>
<p id="demo">这是一个段落</p>
<button type="button" onclick="displayDate()">显示日期</button>
```

​	023.js:

```js
function displayDate(){
	document.getElementById("demo").innerHTML = Date();
}
```

### 	3.事件方式

​	在事件标签中加入对应属性

​	024.html:

```html
<button onclick="alert('坤坤好帅');">鸡你太美</button>
<button onclick="alert('xss2');">
    弹窗按钮
</button>


<button onclick="alert('xss');">
    只因你太美
</button>
```

### 	4.超链接引入方式

​	025.html:

```html
<a href="javascript:alert('你就知道了!!');">百度一下</a>

<a href="javascript:alert('xss');">网易云音乐</a>
<a href="javascript:alert('123');">点我弹窗</a>

```



## 作业

1. 制作登陆：
   要求用户输入用户名和密码，三次之内如果输入正确，弹出“登陆成功”。否则弹出“登陆失败”。

```html
    账号:<input type="text" name="user" id="user">
    密码:<input type="password" name="pwd" id="pwd">
    <button type="button" onclick="login()">登录</button>


<script>
    var id = 'admin';
    var passwd='123456'
    var loginTimes = 0;

    function login(){
        var inputUserId = document.getElementById('user').value;
        var inputPwd = document.getElementById('pwd').value;

       
        loginTimes += 1;
        console.log(inputUserId);
        console.log(inputPwd);
        console.log('logintimes:'+loginTimes)
        if(loginTimes <= 3 && inputUserId==id && inputPwd==passwd){
            alert('登陆成功');
        }else if(loginTimes >= 3){
            alert('登录超过三次,账号已被锁定');
        }else{
            alert('密码错误'+loginTimes+'次,密码错误三次账号将被锁定!');
        }

    }

</script>
```



2. 用代码实现

   输入一数字作为秒数，在控制台按小时，分钟，秒的格式输出（ 如输入600，页面显示：0小时10分0秒 ）

   ```html
   输入秒数:<input type="text" name="inputSe" id="inputS">
   <button type="button" onclick="getTimes()">计算</button>
   
   <script>
       function getTimes(){
           var s = document.getElementById('inputS').value;
           console.log(s)
           var sec = s;
           var minute = 0;
           var hour = 0;
   
           if(sec>=60){
               minute = parseInt(sec/60);
               if(minute >= 60 ){
                   hour = parseInt(minute/60);
                   minute = minute % 60;
               }
               sec = sec % 60;
           }
   
           console.log(s+'秒='+hour+'小时'+minute+'分钟'+sec+'秒');
   
       }
   
   </script>
   ```

   

3. 用三元运算符实现<br>

   1）小明和妈妈约定，期末考试如果语文数学成绩都是满分100，<br>

     周末妈妈就带他去游乐园，否则就只有在家改错题<br>

   

   2）小明考了双百分，妈妈周末带小明去游乐园玩了一天，<br>

     随后和小明约定，如果明年小明还能拿到双百分并<br>

     且奥数能够获得华杯赛前90名就带他去迪斯尼乐园，<br>

     否则就只有后年再努力了<br>

   ```html
   今年的数学成绩<input type="text" id="math1">
   今年的语文成绩<input type="text" name="" id="chinese1">
   <button type="button" onclick="func1()">计算</button>
   <br>
   明年的数学成绩<input type="text" name="" id="math2">
   明年的语文成绩<input type="text" name="" id="chinese2">
   明年的奥数排名<input type="text" name="" id="mathRank">
   <button type="button" onclick="func2()">计算</button>
   
   <script>
       function func1(){
           var math1 = parseInt(document.getElementById('math1').value);
           var chinese1 = parseInt(document.getElementById('chinese1').value);
           console.log(math1+chinese1)
           alert(((math1 + chinese1 == 200) ? "游乐园":"改错题"));
       }
   
       function func2(){
           var math2 = parseInt(document.getElementById('math2').value);
           var chinese2 = parseInt(document.getElementById('chinese2').value);
           var mathRank = parseInt(document.getElementById('mathRank').value);
           console.log(math2+chinese2)
           alert(((math2 + chinese2 == 200 && mathRank < 90) ? "迪士尼乐园":"后年继续努力"));
       }
   
   
       
   
   </script>
   ```

   4. 输入1个同学的成绩，

      60-70为D，70-80为C ，

      80-90为B，90-100为A 

      不及格-60为E 

      最后输出这个同学到底是哪个分段

      ```html
      输入成绩:<input type="text"  id="score">
      <button type="button" onclick="alert(calcScore())">计算档次</button>
      
      <script>
          function calcScore(){
              var score = document.getElementById("score").value;
              console.log(score)
              if(score >= 0 && score <=100){
                  if( score >= 90){
                      return "A";
                  }else if(score >= 80){
                      return "B";
                  }else if(score >= 70){
                      return "C";
                  }else if(score >= 60){
                      return "D";
                  }else{
                      return "不及格"
                  }
              }else{
                  // 		document.getElementById('score').innerHTML = ""
                  return "输入错误,请重新输入"
              }
          }
          
          
      
      
      </script>
      ```

      

5. 输入三个数,找出最大数<br>

   ```html
   输入第一个数: <input type="text" name="" id="num1">
   输入第二个数: <input type="text" name="" id="num2">
   输入第三个数: <input type="text" name="" id="num3">
   <button type="button" onclick="compare()">比较大小</button>
   
   <script>
       function compare() {
           var num = new Array();
           num[0] = parseInt(document.getElementById('num1').value);
           num[1] = parseInt(document.getElementById('num2').value);
           num[2] = parseInt(document.getElementById('num3').value);
           var temp = 0;
           console.log('------传入的值-------')
           for(var k = 0;k < num.length; k++){
               console.log(num[k])
           }
           for (var i = 0; i < num.length; i++) {
               for (var j = 0; j < num.length; j++) {
                   if (num[j] < num[j + 1]) {
                       temp = num[j]
                       num[j] = num[j + 1]
                       num[j + 1] = temp
                   }
               }
           }
           console.log('---------比较后的值----------')
           for(var k = 0;k < num.length; k++){
               console.log(num[k])
           }
           console.log('最大值'+num[0])
       }
   
   </script>
   ```

   