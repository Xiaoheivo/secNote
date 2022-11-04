#! python

import os

#创建文件夹txtfiles
dirPath = "./txtfiles"
if not os.path.exists(dirPath):
    os.makedirs(dirPath)

#在目录中创建1.txt-30.txt并写入内容
for i in range(1,30+1):
    f = open('./txtfiles/'+str(i)+'.txt','w',encoding="UTF-8")
    f.write('这是第'+str(i)+'个文件的内容')
    f.close()


#枚举文件名
fNum = []
for fName in os.listdir(dirPath):
    print(fName)
    
    #将文件名存入列表方便(5.排序输出
    fNum.append(int(fName.split('.')[0]))       


#依次显示文件名和内容
fNum = sorted(fNum)
for j in fNum:
    f = open(dirPath+'/'+ str(j)+'.txt')
    print(str(j)+'.txt\n'+f.read())
    f.close