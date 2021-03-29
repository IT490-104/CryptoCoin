
<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
//Connects to rabbitMQ
$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
$channel = $connection->channel();

//Recieve queue named 'request' from the frontend
$channel->queue_declare('request', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$msg;
//Excute function with the frontend values
$callback = function ($msg) {
	//Stores frontend values to message var.
	$message = $msg->body;
	$statement;
	//Splits frontend message (when'!'appear) and stores it in array
	$array = explode('!',$message);

	echo"Step 2  " . "\n"; 
	//array 0 = username
	//array 1 = password
	//array 2 = option

	$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
	$channel = $connection->channel();
	$channel->queue_declare('result', false, false, false, false);

        echo"Step 3 " . "\n";
	if($array[2]=="register"){ //If frontend message type is register

                //Checks to see if the DB username matches input username
                //If not true execute insert statement
                //Else send False bool
		echo"Step 4 \n";

		echo"Step 5 \n";
		$check = ("SELECT DISTINCT username FROM UserRecords WHERE username= '$array[0]'");

                echo"Step 6: $check\n";

		$database = mysqli_connect("192.168.192.221", "brandan", "Password1!", "IT490DB");
		$retreive = mysqli_query($database, $check);
		while($row = mysqli_fetch_array($retreive)){
			$shortcut = ($row['username']);
			echo $shortcut;
			echo "\n";
		}
                 //If shortcut != $array[0]
                if ($shortcut == ''){
			echo "Inserted statement";
			$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
			$channel = $connection->channel();
			$channel->queue_declare('hello', false, false, false, false);

			$queri = ("INSERT INTO UserRecords (username,password) VALUES ('$array[0]','$array[1]')");
			$msg = new AMQPMessage($queri);
			//Sends queue to DB through the rabbitmq
			$channel->basic_publish($msg, '', 'hello');
                }
                else{ //Else print

                echo "Username in the DB";


			$connection = new AMQPStreamConnection('192.168.192.147', 5672, 'username', 'password');
			$channel = $connection->channel();
			$channel->queue_declare('back2front', false, false, false, false);
			$queri = ("regFailed");
			$msg = new AMQPMessage($queri);
			//Sends queue to DB through the rabbitmq
			$channel->basic_publish($msg, '', 'back2front');


		}
	}
	else{ //If frontend message type is login
		$queri = ("SELECT * FROM UserRecords WHERE username='$array[0]' and password='$array[1]'");
		$msg = new AMQPMessage($queri);
		$channel->basic_publish($msg, '', 'hello');
	}

	$channel->close();
	$connection->close();
};


$channel->basic_consume('request', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>
