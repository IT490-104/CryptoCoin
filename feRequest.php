<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('request', false, false, false, false);

$msg = new AMQPMessage("'$username'!'$password'!'$login'");
$channel->basic_publish($msg, '', 'request');

$channel->close();
$connection->close();
?>

