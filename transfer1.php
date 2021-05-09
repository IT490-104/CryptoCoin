<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = AMQPStreamConnection::create_connection([
    ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
    ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
]);


$channel = $connection->channel();

$channel->queue_declare('ha.transfer2', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;
$callback = function ($msg) {
	//echo ' [x] Received ', $msg->body, "\n";
	$message = $msg->body;
	echo "$message"." is the message in the variable"."\n";

	//-----Create connection to the database

	$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
	//$retrieve = mysqli_query($database, $message);

	if($retrieve = mysqli_query($database, $message)){
		echo "Query ran successfully in the database \n";
	}
	else{
		echo "Query did not run \n";
	}

/*
	$connection = AMQPStreamConnection::create_connection([
	    ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
	    ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
	    ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
	]);

	$channel = $connection->channel();
	$channel->queue_declare('ha.result0', false, false, false, false);

	$msg = new AMQPMessage('good');
	$channel->basic_publish($msg, '', 'ha.result0');

	echo " [x] Sent Response Message to Front End";

	$channel->close();
	$connection->close();
*/

};

$channel->basic_consume('ha.transfer2', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>

