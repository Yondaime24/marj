#include <iostream>
class a {

};

class test {
public:
  a create() {
    return new *a;
  }
};
int main() {

  return 0;
}