<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

$callback = function ($msg) {
   echo $msg->body, "\n";

$table = $msg->body;
$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
$retrieve = mysqli_query($database, $table);

while ($row = mysqli_fetch_array($retrieve)){
    echo $row['username'] . $row['password'];
}

};




$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>

