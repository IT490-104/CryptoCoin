<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = AMQPStreamConnection::create_connection([
    ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
]);

$channel = $connection->channel();

$channel->queue_declare('ha.trigger', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
	$message = $msg->body;
	file_put_contents('triggerFile.txt', "$message");
};

$channel->basic_consume('ha.trigger', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
