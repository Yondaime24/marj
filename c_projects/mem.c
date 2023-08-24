#include <stdio.h>
#include <stdlib.h>

int main() {
  char *name;
  name = (char *) malloc(512);
  name = (char *) realloc(name, 512 * 512);
  
  return 0;
}