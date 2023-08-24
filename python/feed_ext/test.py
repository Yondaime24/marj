import os
import sys
# print(__file__)
# print(sys.argv)
# def copy_bat():
#   #--This is the executable file
#   bin_file = "FEED_extension.exe"
#   bat_name = os.path.abspath(bin_file)
#   #--if the bat is exists in the directory then 
#   #--Copy the bat to the start up
#   if os.path.exists(bat_name):
#     #--Command for getting the path of the startup
#     start_app = os.popen("""echo %appdata%\\Microsoft\\Windows\\Start Menu\\Programs\\Startup""").read().replace("\n", "")

#     #--Check if there is a exist in file then if file is exists
#     #--then kill the process to update the file
#     if os.path.exists(start_app + "test.py"):
#       #kill the process 
#       #pass
#       os.popen("taskkill /F /IM FEED_extension.exe")
#     cmd = f'xcopy "{bat_name}" "{start_app}" /c'
#     #copy the file
#     os.popen(cmd)
#     os.popen(start_app + bin_file)

t = os.path.abspath("temp");
if t[0]:
  while True:
    pass