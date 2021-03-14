#!/usr/bin/php

<?php
//This will import all the needed utilities/packages to make the script work
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//This will establish a connection to the RabbitMQ
$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

//This will specify that we want to read from the "HELLO" queue. queue name is sensitive
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

//Listening for any messages coming in from the queue specified above
//Will print out the incoming messages to the screen. This is not critical to functionality, useful for troubleshooting
$callback = function ($msg) {
   echo $msg->body, "\n";

//Connecting to the Database
//Takes any incoming message and assigns it to the "$table" variable
$table = $msg->body;
$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
$retrieve = mysqli_query($database, $table);

//
while ($row = mysqli_fetch_array($retrieve)){
        //echo $row['username'] . $row['password'];
        $shortcut =  ($row['username'] . $row['password']);
        echo $shortcut;
        $connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
        $channel = $connection->channel();
        
   
        //This will specify that we want to write to the "RESULT" queue. queue name is sensitive
        $channel->queue_declare('result', false, false, false, false);

        $msg = new AMQPMessage($shortcut);
        $channel->basic_publish($msg, '', 'result');

   
        //Closes out the connection to the "RESULT" queue. We no longer need to write
        $channel->close();
        $connection->close();

}

};


//This will specify that we want to continue reading from the "HELLO" queue.
$channel->basic_consume('hello', '', false, true, false, false, $callback);

//This will keep the program from closing after one message. Program will only stop when user presses CTRL + C
while ($channel->is_consuming()) {
    $channel->wait();
}

//Closes out the connection to the "HELLO" queue. We no longer need to read from it
$channel->close();
$connection->close();

?>
