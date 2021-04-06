<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//-----Parsing data.json file and creating variable that contains the information-----

$jsondata = file_get_contents("data.json");
$json = json_decode($jsondata,true);

$part1 = $json['asset_id_base'];
$part2 = $json['rate'];

echo $json['asset_id_base'] . " <--- Cryptocoin" . "\n";
echo $json['rate'] . " <--- Value in USD" . "\n";

//$command = "UPDATE currency SET value = '$part2' WHERE coin = '$part1'";

$command = $part1 . " " . $part2;

//-----Sending API values to the Database via RabbitMQ-----

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage("$command");
$channel->basic_publish($msg, '', 'hello');

echo "Sent updated cryptocurrency information to the Database \n";

$channel->close();
$connection->close();
?>
