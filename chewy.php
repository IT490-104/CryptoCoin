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

$channel->queue_declare('ha.result0', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

$callback = function ($msg) {
	echo $msg->body, " <- Recieved from the result queue \n";

	$table = $msg->body;

	$expand = $msg->body;
	$array = explode('!',$expand);

	if($table=="loginSuccess"){

		$connection = AMQPStreamConnection::create_connection([
   			 ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
   			 ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
   			 ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/'] 
		]);


		$channel = $connection->channel();

		$channel->queue_declare('ha.back2front', false, false, false, false);

		$msg = new AMQPMessage('loginSuccess');
		$channel->basic_publish($msg, '', 'ha.back2front');

		echo "loginSuccess written to back2front queue \n";

		$channel->close();
		$connection->close();
	}

	if($table=="loginFailed"){

		$connection = AMQPStreamConnection::create_connection([
                         ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']  
                ]);

		$channel = $connection->channel();

		$channel->queue_declare('ha.back2front', false, false, false, false);

		$msg = new AMQPMessage('loginFailed');
		$channel->basic_publish($msg, '', 'ha.back2front');

		echo "loginFailed written to back2front queue \n";

		$channel->close();
		$connection->close();
	}

	if($table=="regSuccess"){

		$connection = AMQPStreamConnection::create_connection([
                         ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']  
                ]);

		$channel = $connection->channel();

		$channel->queue_declare('ha.back2front', false, false, false, false);

		$msg = new AMQPMessage('regSuccess');
		$channel->basic_publish($msg, '', 'ha.back2front');

		echo "regSuccess written to back2front queue \n";

		$channel->close();
		$connection->close();
	}

	if($array[0]=="passValue"){

		$answer = $array[1];

		$connection = AMQPStreamConnection::create_connection([
                         ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                         ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']  
                ]);

		$channel = $connection->channel();

		$channel->queue_declare('ha.transfer3', false, false, false, false);

		$msg = new AMQPMessage($answer);
		$channel->basic_publish($msg, '', 'ha.transfer3');

		echo "Requested cryptocurrency value SENT to Front End ON ha.transfer3 queue \n";

		$channel->close();
		$connection->close();
	}


};

$channel->basic_consume('ha.result0', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
