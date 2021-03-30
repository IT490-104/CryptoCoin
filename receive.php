
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('back2front', false, false, false, false);

$msg;
$callback = function ($msg) {

$expand = $msg->body;

if($expand=='loginSuccess'){
 header ("Location: home.html");
}

if($expand=='loginFailed'){
echo  'Login failed, please try again';
}

if($expand=='regSuccess'){
header ("Location: index.html");
}

if($expand=='regFailed'){
echo 'Registeration failed, please try again';
}

};


$channel->basic_consume('back2front', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();

$channel->close();
$connection->close();
}
?>
