<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

$callback = function ($msg) {
	echo $msg->body, " <- Recieved from the hello queue \n";

	$table = $msg->body;
	$expand = $msg->body;

	echo "$expand" . " <- expand variable is set to this \n";
	//$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
	//$retrieve = mysqli_query($database, $table);


	//echo "$expand" . "hello :)";

	$array = explode(' ',$expand);

	echo "$array[0]" . " <- This is the word recieved in the array \n";

	if($array[0]=="SELECT"){

		echo "SELECT query recieved \n";

		$database = mysqli_connect("192.168.192.221", "luz", "Password1!", "IT490DB");
		$retrieve = mysqli_query($database, $table);


		while ($row = mysqli_fetch_array($retrieve)){
			$shortcut =  ($row['username'] . $row['password']);
			echo $shortcut;

			$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
			$channel = $connection->channel();

			$channel->queue_declare('result', false, false, false, false);

			$msg = new AMQPMessage($shortcut);
			$channel->basic_publish($msg, '', 'result');

			echo "\n Result from SELECT statement written out to result queue \n";

			$channel->close();
			$connection->close();


		}

	}

	if($array[0]=="INSERT"){
		$database = mysqli_connect("192.168.192.221", "luz", "Password1!", "IT490DB");
		if($retrieve = mysqli_query($database, $table)){
			//$retrieve  = mysqli_query($database, $table);

			$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
			$channel = $connection->channel();

			$channel->queue_declare('result', false, false, false, false);

			$msg = new AMQPMessage("success");
			$channel->basic_publish($msg, '', 'result');

			echo "Result from INSERT statement written out to the result queue \n";
			echo "INSERT statement executed successfully \n ";
			$channel->close();
			$connection->close();


		}
		else{
			$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
			$channel = $connection->channel();

			$channel->queue_declare('result', false, false, false, false);

			$msg = new AMQPMessage("fail");
			$channel->basic_publish($msg, '', 'result');

			$channel->close();
			$connection->close();

			echo "Result from INSERT statement written out ot the result queue \n";
			echo "INSERT statement failed";

		}

	}

};




$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
