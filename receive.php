
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = AMQPStreamConnection::create_connection([
    ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
]);

$channel = $connection->channel();

$channel->queue_declare('ha.back2front', false, false, false, false);

$msg;
$callback = function ($msg) {

$expand = $msg->body;

if($expand=='loginSuccess'){
 header ("Location: home.php");
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


$channel->basic_consume('ha.back2front', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();

$channel->close();
$connection->close();
}
?>
