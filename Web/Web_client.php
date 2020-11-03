<?php

$PORT = 5000;
$address = "";

$socket = socket_create(AF_INET, SOCK_STREAM, 0 );

$socket_connect=socket_connect($socket,$address,$PORT);

$text= "Hello, java";

socket_write($socket,$text."\n", strlen($text)+1);

while($out = socket_read($socket,2048)){
    echo $out;
}

?>
