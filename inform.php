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

	$message = $msg->body;
	echo "$message"." is the message in the variable"."\n";

	$array = explode('!', $message);

	if($array[0]=="pass"){
		$database = mysqli_connect("192.168.192.221","manvir","Password1!","IT490DB");
		$command = "SELECT * FROM currency WHERE coin = '$array[1]';";
		$result = mysqli_query($database, $command);

		while($row = mysqli_fetch_array($result)){
			$answer = $row['value'];
			$answer1 = ('passValue!' . $answer) ;
			//----------------------

			$connection = AMQPStreamConnection::create_connection([
			    ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
			    ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
			    ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
			]);

			$channel = $connection->channel();
			$channel->queue_declare('ha.result0', false, false, false, false);

			$msg = new AMQPMessage($answer1);
			$channel->basic_publish($msg, '', 'ha.result0');

			echo "Value of requested cryptocurrency has been written to the ha.result0 queue\n";

			$channel->close();
			$connection->close();

			//----------------------
		}
	}
};

$channel->basic_consume('ha.transfer2', '', false, true, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>

