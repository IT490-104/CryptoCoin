
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('back2front', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('back2front', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();

$channel->close();
$connection->close();
}
?>
