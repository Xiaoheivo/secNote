f = open('test.txt','r+')
# f.write("abcdefghijklmn")
print(f.read())
f.seek(5)
# print(f.read())
f.write('\n')
f.write('ccccc')
print(f.read())
