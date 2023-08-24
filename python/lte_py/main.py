from serial import Serial
from time import sleep
import urllib.parse as parse
BUFF_SIZE = 128

# s = Serial("COM1")
# print(s)
def data_process(data):
  fdata = data.split(b"\r\n\r\n")
  if len(fdata) > 1:
    print(fdata[1])

def config():
  # configuration of the port
  # 
  pass

try:
  s = Serial("COM1", 115200, timeout=0)
  #s.write(b"+++\r\n")
  #sent_data = "data=" + parse.quote("data goes here")
  #sent_data = sent_data + "&age=1232434hfdsjlhahsfsafFDSAFAfsdf hlkfdsahfla fdsa#$%^&*#@$%%&^%^$&$&^%$^&"
  #sent_len = len(sent_data)
  s.write(f"GET /db.json HTTP/1.1\r\nHost: ltedtu-default-rtdb.firebaseio.com\r\nConnection: Keep-Alive\r\n\r\n".encode())
  while True:
    data = b""
    tmp_data = s.read(BUFF_SIZE)
    if len(tmp_data) > 0:
      data = data + tmp_data
      while True:
        tmp_data = s.read(BUFF_SIZE)
        if len(tmp_data) <= 0:
          break
        data = data + tmp_data
      data_process(data)
      sleep(2)
      s.write(f"GET /api.php?url=/ HTTP/1.1\r\nHost: info.mctech4.com\r\nConnection: Keep-Alive\r\n\r\n".encode())
except Exception as er:
  print("cannot open the port, please check the open port")