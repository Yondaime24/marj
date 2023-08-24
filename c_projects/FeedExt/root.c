#include <stdio.h>
#include <Ws2tcpip.h>
#include <winsock2.h>
#include <string.h>
#include <stdint.h>

int main(int argc , char *argv[]) {
  WSADATA wsa;
  SOCKET s , new_socket;
  struct sockaddr_in server , client;
  uint32_t c;
  uint16_t i;
  char buffer[1024];// Buffer for receiving the data from the client
  uint16_t rv;
  printf("[0] Network Initializing...\n");
  if (WSAStartup(MAKEWORD(2,2),&wsa) != 0)
  {
    printf("[0] Failed. Error Code : %d",WSAGetLastError());
    return 1;
  }
  printf("[1] Network initialized!\n");
  //Create a socket
  printf("[0] Creating a socket...\n");
  if((s = socket(AF_INET , SOCK_STREAM , 0 )) == INVALID_SOCKET) {
    printf("[0] Could not create socket : %d" , WSAGetLastError());
  }
  printf("[1] Socket created!\n");
  //Prepare the sockaddr_in structure
  server.sin_family = AF_INET;
  server.sin_addr.s_addr = inet_addr("127.0.0.1");
  server.sin_port = htons(8000);

  // for client
  // if ((c = connect(s, (struct sockaddr *) &server, sizeof(server))) < 0) {
  //   printf("Failed to connect to the server!");
  //   return 0;
  // }
  // end for client

  //Bind
  printf("[0] Binding...\n");
  if( bind(s ,(struct sockaddr *)&server , sizeof(server)) == SOCKET_ERROR)
  {
    printf("[0] Bind failed with error code : %d" , WSAGetLastError());
  }
  printf("[1] Binded!\n");
  //Listen to incoming connections
  listen(s , 3);
  //Accept and incoming connection
  c = sizeof(struct sockaddr_in);

  while (new_socket = accept(s , (struct sockaddr *)&client, &c)) {
    printf("[0] Waiting network handshake!\n");  
    if (new_socket == INVALID_SOCKET) {
      printf("[0] Accept failed with error code : %d" , WSAGetLastError());
    }
    printf("[1] Handshake accepted [%d]!\n", new_socket);
    while (1) {
      /*===================== Processing here  ==================*/
      buffer[sizeof(buffer)-1] = '\0';
      rv = recv(new_socket, buffer, sizeof(buffer), 0);
      printf("%s", buffer);
      if (rv < sizeof(buffer)) 
        break;
      /*====================== End Processing ======================== */
    }
    char *resp = "HTTP/1.0 200 OK\r\nContent-Type:text/html\r\nContent-Length:4\r\n\r\nhell";
    send(new_socket, resp, strlen(resp), 0); // Sending the data to the client
    printf("[0] Shutting down connection [%d]...\n", new_socket);
    shutdown(new_socket, 2); // Shutdown the connection
    printf("[1] Connection shutdown [%d]!\n", new_socket);
  }
  closesocket(s);
  WSACleanup();
  return 0;
}
