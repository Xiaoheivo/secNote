import sys
with open('test.txt') as fp:
    while 1:
        line = fp.readline()
        print(line,end='')
        if (line == ""):
            sys.exit()