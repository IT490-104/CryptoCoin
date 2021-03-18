<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('request', false, false, false, false);

//$msg = new AMQPMessage('carl!');
//$msg2 = new AMQPMessage('bacon!');
//$msg3 = new AMQPMessage('register!');
$msg4 = new AMQPMessage('luz!chicken!register');


//$channel->basic_publish($msg, '', 'request');
//$channel->basic_publish($msg2, '', 'request');
//$channel->basic_publish($msg3, '', 'request');
$channel->basic_publish($msg4, '', 'request');


echo " [x] Sent Message \n";

$channel->close();
$connection->close();
?>
