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

$channel->queue_declare('ha.hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;

$callback = function ($msg) {
	echo $msg->body, " <- Recieved from the hello queue \n";

	$table = $msg->body;
	$expand = $msg->body;

	//echo "$expand" . " <- expand variable is set to this \n";

	$array = explode(' ',$expand);

	echo "$array[0]" . " <- This is the word recieved in the array \n";

	//-----Updating cryptocurrency information in Database
	$database1 = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");

	
	switch($array[0]){
		case "BTC":
			$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
			echo $command . "\n";
			if(mysqli_query($database1, $command)){
				echo "Updated information in the database\n";
			}
			else{
				echo "Information was NOT updated\n";
			}
			break;
		case "LTC":
			$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
			echo $command . "\n";
			if(mysqli_query($database1, $command)){
				echo "Updated information in the database\n";
			}
			else{
				echo "Information was NOT updated\n";
			}
			break;
		case "XRP":
			$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
			echo $command . "\n";
			if(mysqli_query($database1, $command)){
				echo "Updated information in the database\n";
			}
			else{
				echo "Information was NOT updated\n";
			}
			break;
		case "DOGE":
			$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
			echo $command . "\n";
			if(mysqli_query($database1, $command)){
				echo "Updated information in the database\n";
			}
			else{
				echo "Information was NOT updated\n";
			}
			break;
		case "ETH":
			$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
			echo $command . "\n";
			if(mysqli_query($database1, $command)){
				echo "Updated information in the database\n";
			}
			else{
				echo "Information was NOT updated\n";
			}
			break;
	}
	


	/*

	if($array[0] == 'BTC' or 'LTC' or 'XRP' or 'DOGE' or 'ETH')){
		$command = "UPDATE currency SET value = '$array[1]' WHERE coin = '$array[0]'";
		echo $command . "\n";
		if(mysqli_query($database1, $table)){
			echo "Updated information in the database\n";
		}
		else{
			echo "Information was NOT updated\n";
		}
	}
	*/

	//-----User Registration-----
	if($array[0]=="SELECT"){

		echo "SELECT query recieved \n";

		$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
		$retrieve = mysqli_query($database, $table);


		if ($row = mysqli_fetch_array($retrieve)){

			$connection = AMQPStreamConnection::create_connection([
			        ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
			        ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
			        ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']
			]);

			$channel = $connection->channel();

			$channel->queue_declare('ha.result', false, false, false, false);
			$channel->queue_declare('ha.result0', false, false, false, false);


			$msg = new AMQPMessage("loginSuccess");
			$channel->basic_publish($msg, '', 'ha.result');
			$channel->basic_publish($msg, '', 'ha.result0');

			echo "loginSuccess written to result queue \n";

			$channel->close();
			$connection->close();


		}
		else{
			$connection = AMQPStreamConnection::create_connection([
                                ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                                ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                                ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']
                        ]);

			$channel = $connection->channel();

			$channel->queue_declare('ha.result0', false, false, false, false);

			$msg = new AMQPMessage("loginFailed");
			$channel->basic_publish($msg, '', 'ha.result0');

			echo "loginFailed written to result queue \n";

			$channel->close();
			$connection->close();

		}

	}

	if($array[0]=="INSERT"){
		$database = mysqli_connect("192.168.192.221", "manvir", "Password1!", "IT490DB");
		if($retrieve = mysqli_query($database, $table)){

			$connection = AMQPStreamConnection::create_connection([
                                ['host' => '192.168.192.147', 'port' => '5672', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                                ['host' => '192.168.192.147', 'port' => '5673', 'user' => 'username', 'password' => 'password', 'vhost' => '/'],
                                ['host' => '192.168.192.147', 'port' => '5674', 'user' => 'username', 'password' => 'password', 'vhost' => '/']
                        ]);

			$channel = $connection->channel();

			$channel->queue_declare('ha.result0', false, false, false, false);

			$msg = new AMQPMessage("regSuccess");
			$channel->basic_publish($msg, '', 'ha.result0');

			echo "regSuccess written to the result queue \n";
			$channel->close();
			$connection->close();


		}

	}

};




$channel->basic_consume('ha.hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
