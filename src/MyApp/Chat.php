<?php 
	namespace MyApp;
	use Ratchet\MessageComponentInterface;
	use Ratchet\ConnectionInterface;
	
	class Chat implements MessageComponentInterface {
		protected $clients;
		// Screenshot number
		protected $tp;

		public function __construct() {
			$this->clients = new \SplObjectStorage;
			$this->tp = 1;
		}

		public function onOpen(ConnectionInterface $conn) {
			$this->clients->attach($conn);
			echo "New connection ! ({$conn->resourceId})\n";
			$this->tp = 1;
		}

		public function onMessage(ConnectionInterface $from, $msg) {
			//$numRecv = count($this->clients) - 1;
			//echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
			
			echo sprintf('Received message %s from Connection %d' . "\n",  $msg, $from->resourceId);
			
			/*foreach ($this->clients as $client) {
				if ($from !== $client) {
					$client->send($msg);
				}a         
			}*/

			$msgDecode = json_decode($msg);

			if($msgDecode[0] == 0) {
				// Take keyCode
				$key = $msgDecode[1];

				// Emulate Key
				exec('./fakeKey '.$key.' '.$msgDecode[2]);
			} elseif ($msgDecode[0] == 1) {
				// Emulate mouse action
				exec('./mouse '.$msgDecode[1].' '.$msgDecode[2].' '.$msgDecode[3]);
			} elseif ($msgDecode[0] == 3) {
				// Send Screenshot
				$tp = $this->tp;
				$prevTp = $tp-1;

				// Take screenshot
				exec("import -window root ../images/screenshot$tp.jpeg");
				exec("rm ../images/screenshot$prevTp.jpeg");
				$image = "<img id='screenshot' src='./images/screenshot".$tp.".jpeg' />";
				//print_r($image);
				$from->send($image);
				$this->tp +=1;
			}
		}

		public function onClose(ConnectionInterface $conn) {
			$this->clients->detach($conn);
			$prevTp = $this->tp - 1;
			echo "Connection {$conn->resourceId} has disconnected\n";
			exec("mv ../images/screenshot$prevTp.jpeg ../images/screenshot0.jpeg");
		}

		public function onError(ConnectionInterface $conn, \Exception $e) {
			echo "An error has occured: {$e->getMessage()}\n";
			$conn->close();
		}
	}
?>