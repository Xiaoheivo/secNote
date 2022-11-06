#! python

import requests,re


url = 'https://changyongdianhuahaoma.bmcx.com/'
r = requests.get(url)
tmp = r.text        #获取网页内容


# #提取电话号码
tmp2 = re.findall(r'<td>((?!电话号码).+)</td>',tmp)

for i in range(0,len(tmp2),2):
    print(str(tmp2[i]).replace(" ",'') +":"+tmp2[i+1])
