from libc.stdlib cimport system

def c_command(cmd):
  system(cmd)