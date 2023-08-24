from pystray import Icon as icon, Menu as menu, MenuItem as item
from time import sleep
from PIL import Image, ImageDraw
from threading import Thread
import pyodbc

def ServerStart():
  i = 0
  while True:
    sleep(2)
    txt = open("msg.txt", "wb")
    msg = f"You  have {i} new message! dfsafhl hflshfkjhsad flkdsajfh lgdskjgfklsgdaflk gdskfgkladf sglkgafdslfgj"
    txt.write(msg.encode())
    icon.notify(msg)
    i = i + 1
    txt.close()

def create_image(width, height, color1, color2):
  image = Image.new("RGB", (width, height), color1)
  dc = ImageDraw.Draw(image)
  dc.rectangle(
    (width / 2, 0, width, height / 2), fill=color2)

  dc.rectangle(
    (0, height / 2, width / 2, height), fill=color2)
  return image
t1 = Thread(target=ServerStart)
t1.start()

icon = icon(
  "test", 
  icon=create_image(64, 64, "black", "white"),
  menu=menu(
    item(
      "Settings",
      lambda icon, item: icon.notify("Settings")
    )
  )
)

icon.run()