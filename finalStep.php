<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('result0', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

$callback = function ($msg) {
	echo $msg->body, " <- Recieved from the result queue \n";

	$table = $msg->body;

	if($table=="loginSuccess"){

		$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
		$channel = $connection->channel();

		$channel->queue_declare('back2front', false, false, false, false);

		$msg = new AMQPMessage('loginSuccess');
		$channel->basic_publish($msg, '', 'back2front');

		echo "loginSuccess written to back2front queue \n";

		$channel->close();
		$connection->close();
	}

	if($table=="loginFailed"){

		$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
		$channel = $connection->channel();

		$channel->queue_declare('back2front', false, false, false, false);

		$msg = new AMQPMessage('loginFailed');
		$channel->basic_publish($msg, '', 'back2front');

		echo "loginFailed written to back2front queue \n";

		$channel->close();
		$connection->close();
	}

	if($table=="regSuccess"){

		$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
		$channel = $connection->channel();

		$channel->queue_declare('back2front', false, false, false, false);

		$msg = new AMQPMessage('regSuccess');
		$channel->basic_publish($msg, '', 'back2front');

		echo "regSuccess written to back2front queue \n";

		$channel->close();
		$connection->close();
	}


};




$channel->basic_consume('result0', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
