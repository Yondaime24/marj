#--Basic Socket programming,
#--Communication between the browser and user computer

import socket
import requests
import base64
import os
import json
from time import sleep
import sys
import subprocess
from c_sys import c_command
from netlinkz import NetFile

ar = sys.argv
ar_len = len(ar)

is_run_from_bat = False
if ar_len > 1:
  # if the startup is detected in the second argv then don't run the copy_bat
  if ar[1] == "startup":
    is_run_from_bat = True

def copy_bat():
  #--This is the executable file
  exe = ""
  if ar_len > 0:
    t_ =  ar[0].split("\\")
    if len(t_) > 0:
      exe = t_[len(t_)-1]
  bin_file = exe
  t_file = bin_file.split(".")

  if t_file[0]:
    bat_file = t_file[0] + ".bat"
    bat_name = os.path.abspath(bat_file)
    #--if the bat is exists in the directory then 
    #--Copy the bat to the start up
    #--Command for getting the path of the startup
    p = os.path.abspath(exe).replace(exe, "")
    fp = open(bat_name, "w")
    fp.write("cd " + p)
    fp.write("\n");
    fp.write("start " +exe + " startup")
    fp.close()
    start_app = os.popen("""echo %appdata%\\Microsoft\\Windows\\Start Menu\\Programs\\Startup""").read().replace("\n", "")

    #--Check if there is a exist in file then if file is exists
    cmd = f'xcopy "{bat_name}" "{start_app}" /c /Y'
    #copy the file
    os.popen(cmd)
    sleep(1)
    if os.path.exists(bat_name):
      os.remove(bat_file)
try: 
  if not is_run_from_bat:
    copy_bat()
  #--config file 
  config = "config.conf"
  #--Default Configuration file
  json_config = """{ "host": "127.0.0.1", "port": 8558}"""
  #--Convert the plain text json, to json object
  json_config = json.loads(json_config)
  #--Check if path exist
  #--If path is not exist then use the default config {json_config variable}
  if os.path.exists(config):
    with open(config) as config_file:
      fconfig = config_file.read()
      try:
        json_config = json.loads(fconfig)
      except Exception as er:
        pass
  #-- Host Ip
  host = json_config.get("host")
  #-- Port
  port = json_config.get("port")
  #-- Maximum buffer of 4KB
  buffer_size = 0x1000
  host_ip = socket.gethostbyname(host)
  s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
  s.bind((host_ip, port))
  s.listen(10)
  print(f"Host: {host}:{port}")
  print("Running...")
  while True:
    feed_ip = b""
    feed_path = b""
    powerpoint = b""
    conn, addr = s.accept()
    #--Receive the data from the browser or client
    data = conn.recv(buffer_size)
    #--Split the data line by line(using crlf) \r\n
    data_header = data.split(b"\r\n")
    #--get the length of the object data_header
    header_len = len(data_header)
    #--path is the storage of the url path example, /test.html?id=1234
    path = ""
    if header_len > 0:
      #--Extract the first header
      #--the first header content example = GET / HTTP/1.1
      #--So the index 0 is the type of request, GET, POST, PUT, DELETE
      #--then index 1 is the url path, requested by the browser or client
      t_path = data_header[0].split(b" ") #index 0 of the data_header contains ex.: GET / HTTP/1.1
      t_path_len = len(t_path)
      #--To avoid error so we need to check the t_path object to extract atleast length of 3
      if t_path_len > 2:
        #--store the path located in index 0 of t_path
        path = t_path[1]
    if path:
      #--example url path : /feed/172.30.6.194/FCB_ELearning/
      #--Get all tokens, split the string by /
      u_data = path.split(b"/")
      u_data_len = len(u_data)
      if u_data_len > 4:
        i = 2
        #--@while: append all the url as feed address
        while i < u_data_len:
          if u_data[i]:
            #--appending the file until we get the
            #--172.30.6.194/FCB_ELearning/
            feed_ip = feed_ip + u_data[i] + b"/"
          i = i + 1
        #--index 1 is the the route
      if u_data_len > 1:
        feed_path = u_data[1]
    if feed_path == b"ppsx":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.ppsx")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"ppt":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.ppt")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"gif":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.gif")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"png":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.png")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"jpg":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.jpg")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"jpeg":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.jpeg")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"pptx":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.pptx")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"doc":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.doc")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"docx":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.docx")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"xlsx":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.xlsx")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"mp4":    
      if feed_ip:
        powerpoint = feed_ip + b"api.php?route=feed/task"
      if powerpoint:
        try:
          var = requests.get(b"http://" + powerpoint)
          t_con = var.text.split("^")
          if len(t_con) > 1:
            data_bin = base64.decodebytes(t_con[1].encode())
            try:
              t_path = os.path.abspath("test.mp4")
              f = open(t_path, "wb")
              f.write(data_bin)
              f.close()
              c_command(b"start cmd.exe /c \"" + t_path.encode() + b"\"")
            except Exception as er:
              print(er)
              #--pass
        except Exception as err1:
          print(err1)
          #pass
    elif feed_path == b"file":
      #http://url/file/http://netlinz/doc.docs
      #extending the python program
      if path:
        NetFile(path, requests)
    conn.sendall(f"HTTP/1.1 200 OK\r\nAccess-Control-Allow-Headers:*\r\nAccess-Control-Allow-Origin:*\r\nContent-Length: 1\r\n\r\n1".encode())
    conn.close()
except Exception as er1:
  print(er1)