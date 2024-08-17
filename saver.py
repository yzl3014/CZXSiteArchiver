# -*- coding:utf-8 -*-
# 除了import进来的，另需安装"lxml"
import requests, os, time
from bs4 import BeautifulSoup

LOCAL_PATH = "C:/Users/Administrator/Desktop/czxWebsite/web/"  # 存储文档路径，结尾要有斜杠
headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36"
}  # 自定义header

rootURL = "https://www.chuzixuan.com/"  # 域名网址，结尾要有斜杠
domain = "chuzixuan.com"  # 域名
displayURL = "http://localhost/web/"  # 展示链接，如“ https://example.com/save/ ”，后面紧接着存档的日期数字

# 获取首页
res = requests.get(rootURL, headers=headers)
soup = BeautifulSoup(res.text, "lxml")
links = []
for a in soup.find_all("a"):  # 获取所有<a href=''>
    if rootURL in a["href"] and "#comments" not in a["href"]:  # 仅添加站内链接，排除评论链接
        links.append(a["href"])
# 用于获取非当前域名下的外部文件
for js in soup.find_all("script"):  # 获取所有<script src=''>
    if domain in js.get("src", ""):  # 如果是内嵌脚本，则没有src属性，就会报错
        links.append(js["src"])
for link in soup.find_all("link"):  # 获取所有<link href=' .css'>
    if domain in link.get("href", ""):
        if ".css" in link["href"]:
            links.append(link["href"])
links = list(set(links))  # 清除重复链接

# 下载
os.chdir(LOCAL_PATH)  # 跳转到文件夹
folderName = str(time.strftime("%Y%m%d%H%M%S", time.localtime()))  # 按时间命名，格式为“年月日时分秒”
os.mkdir(folderName)  # 创建文件夹
os.chdir(LOCAL_PATH + folderName)  # 跳转到新文件夹
for link in links:
    pageURI = link.split("/", 3)[3]  # 路径数组，前面两个成员是"https"和域名
    pageName = link.split("/")[len(link.split("/")) - 1]  # 文件名，例如“123.html”“123.js”，空则为index
    pageURL = rootURL + pageURI  # 当前页面的完整网址
    if "/" in pageURI:  # 处理多层文件夹
        URIArray = link.split("/")  # 分割路径，得到各层文件夹的名称
        del URIArray[len(URIArray) - 1]  # 删除末尾
        # “删除第一个”重复三次，就相当于删除前三个，即文本"https://"
        del URIArray[0]
        del URIArray[0]
        del URIArray[0]
        currentPath = LOCAL_PATH + folderName + "/"  # 对应的本地路径
        # 按照文件夹层级，创建文件夹。层层深入。
        for name in URIArray:
            if not os.path.exists(name):  # 文件夹已存在，创建就会报错
                os.mkdir(name)
            os.chdir(currentPath + name)
            currentPath += name + "/"
    if pageName == "":  # 处理index页面。例如网址“example.com/123/”，没有文件名则当前页面是index(索引)页
        pageName = "index.html"
    # 输出
    if pageName == "index.html":
        print(pageURL + " -> /" + pageURI + "index.html")
    else:
        print(pageURL + " -> /" + pageURI)
    # 创建并写入文件
    with open(pageName, "+tx", encoding="utf-8") as file:  # 注意此处必须为utf-8，否则页面乱码
        pageCode = requests.get(pageURL, headers=headers).text
        pageCode = pageCode.replace("https://www.chuzixuan.com/", displayURL + folderName + "/")  # 替换为相对目录
        file.write(pageCode)
    os.chdir(LOCAL_PATH + folderName)  # 可能进入了多层目录，需要返回到根目录
print("Folder: " + LOCAL_PATH + folderName)
