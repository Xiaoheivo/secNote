# #! python

f = open("./手机号密码字典.txt","r")
f.seek(12003988,0)
for i in f.readlines(100):
    print(i.rstrip())
f.close()
