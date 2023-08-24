
cdef long long csum():
  cdef long long i = 0
  cdef long long sums = 0
  while i < 200000000:
    sums = sums + i
    i = i + 1
  return sums

def p_sum():
  i = 0
  sums = 0
  while i < 200000000:
    sums = sums + i
    i = i + 1
  return sums

def c_sum():
  return csum()
