#! python
floors = int(input("请输入宝塔层数:"))
print('\n\n##--正立宝塔--##\n')

for i in range(1,floors*2,2):
    space = floors-i//2-1       #计算空格数
    print(' '*space,'*'*i)

print('\n\n##--倒立宝塔--##\n')


for j in range(floors*2-1,0,-2):
    space = floors - j//2-1     #计算空格数
    print(' '*space,'*'*j)