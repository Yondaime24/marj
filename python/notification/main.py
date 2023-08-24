import pyodbc

con = pyodbc.connect("driver=SQL Anywhere 17;eng=netlinkz;dbn=feed17;commlinks=tcpip(host=172.30.6.194:49159);UID=feedadmin;PWD=feedadmin")
cursor = con.cursor()
res = cursor.execute("select * from sub_topics where id = ?",[82])
data = res.fetchall()
data_len  = len(data)

i = 0
while i < data_len:
  print(data[i][1])
  i = i + 1