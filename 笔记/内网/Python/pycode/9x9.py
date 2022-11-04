#! python
for i in range(1,9+1):
    for j in range(1,i+1):
            print(j,'*',i,'=',str(i*j).rjust(2),end='  ')
    print('\n')