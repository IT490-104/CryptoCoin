#!/usr/bin/php

<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage("SELECT * FROM UserRecords WHERE Username = 'Kyle';");
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent Message \n";

$channel->close();
$connection->close();
?>
