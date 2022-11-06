#! python

import re,requests


ss = requests.Session()
url = 'http://lab1.xseclab.com/xss2_0d557e6d2a4ac08b749b61473a075be1/index.php'
r = ss.get(url)
# print(r.text)

s = re.findall(r'(.+)=<',r.text)
s1 = str(s[0]).replace(" ",'')
print(eval(s1))
s2 = eval(s1)
result = {'v':s2}

r2 = ss.post(url,data=result)
r2.encoding='utf-8'
print(r2.text)