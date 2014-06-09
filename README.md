This is a VNC implementation using WebSockets. Ratchet has been used as the websocket library. Both the VNC server and the client have been implemented in PHP. The server will work only in XWindows environment. The client can be any machine. This is a basic implementation. It can be optimized by sending just the modified rectangles from the server inplace of whole screenshots. Also, currently only single keys can be sent, hence capital alphabets and operations like selection using shift won't work.

To run it, execute the Serverside executible PHP script, then connect the client.
Modify the address of the server in the JS file ( currently localhost )

Server Executable - /bin/chat-server.php
Server Code - /src/MyApp/Chat.php
Client - client.html