<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$option = $_POST["option"];
$message = "";

if($option == "login"){
 $username = $_POST["username"];
 $password = $_POST["password"];
 $message = $username . "!" . $password . "!" . $option;
}else{
 $username = $_POST["username"];
 $password_1 = $_POST["password_1"];
 $password_2 = $_POST["password_2"]; 
if($password_1 != $password_2){
  //$error = "true";
   header("Location: register.php?error=true");
   //exit;
  // $message = $username. "!" . $password_1 .  "!" . $password_2 .  "!" . $option;
  }else{  
    $message = $username. "!" . $password_1 .  "!" . $option;
} 

// $message = $username. "!" . $password_1 .  "!" . $password_2 .  "!" . $option;
}

$connection = AMQPStreamConnection::create_connection([
	['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
	['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
	['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']
]);

$channel = $connection->channel();

$channel->queue_declare('ha.request', false, false, false, false);

$msg = new AMQPMessage($message);
$channel->basic_publish($msg, '', 'ha.request');

//echo "Message was sent!";

$channel->close();
$connection->close();

?>
