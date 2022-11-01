strs='123456789'
i=3
print(str(i)+"行"+strs);i=i+1                 # 输出字符串
print(str(i)+"行"+strs[0:-1]);i=i+1           # 输出第一个到倒数第二个的所有字符
print(str(i)+"行"+strs[0]);i=i+1              # 输出字符串第一个字符
print(str(i)+"行"+strs[2:5]);i=i+1            # 输出从第三个开始到第五个的字符
print(str(i)+"行"+strs[2:]);i=i+1             # 输出从第三个开始后的所有字符
print(str(i)+"行"+strs[1:5:2]);i=i+1          # 输出从第二个开始到第五个且每隔一个的字符（步长为2）
print(str(i)+"行"+strs * 2);i=i+1             # 输出字符串两次
print(str(i)+"行"+strs + '你好');i=i+1         # 连接字符串
 
print('------------------------------')
 
print('hello\nrunoob')      # 使用反斜杠(\);+n转义特殊字符
print(r'hello\nrunoob')     # 在字符串前面添加一个 r，表示原始字符串，不会发生转义