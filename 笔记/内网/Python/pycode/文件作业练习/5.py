#! pyhton
import re

# 读取GB2312编码的文档
# f = open('GB2312.txt','r')
# print(f.read())
# f.close()

#显示该文档的二进制内容
# fr = open('GB2312.txt','rb')
# tmp = str(fr.read()).replace("b'",'').replace("'",'').replace('\\r\\n','').replace('\\x','')
# tmpStr = re.findall(r'.{2}',tmp)
# for i in tmpStr:
#     print(bin(int(i,16)),end=', ')
# fr.close()

print()
print()
print()
#转换成utf-8
content = open('GB2312.txt','rb').read()
content = content.decode("GB2312").encode(encoding='UTF-8')


#显示转换后的二进制内容
tmp = str(content).replace("b'",'').replace("'",'').replace('\\r\\n','').replace('\\x','')
tmpStr = re.findall(r'.{2}',tmp)
for i in tmpStr:
    print(bin(int(i,16)),end=', ')
    
#显示转换后的内容
print(content)

f = open('utf8.txt','wb')
f.write(content)
f.close

