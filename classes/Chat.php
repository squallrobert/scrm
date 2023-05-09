<?php
/**
 * Created by PhpStorm.
 * User: Fernando
 * Date: 12/07/2016
 * Time: 16:36
 */

namespace classes;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
	protected $clients;
	private $mapSessionConnections;

	public function __construct() {
		$this->clients = [];
		$this->mapSessionConnections = [];
	}

	public function onOpen(ConnectionInterface $conn) {
		$this->clients[] = $conn;
		//echo "New connection! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $from, $msg) {

		$msgArray = json_decode($msg, true);

		switch($msgArray['tipo']) {
			case "init":
				//Envia duas mensagens:
				//	- usuarios logados recebem o id do usuário que logou
				//	- usuário que logou recebe o id de todos os usuários logados
				foreach($this->clients AS $client) {
					if ($from != $client) {
						$client->send(json_encode([
													  "tipo" => "login",
													  "usuarios_logados" => $msgArray['id_usuario'],
												  ]));
					} else {
						$client->send(json_encode([
													  "tipo" => "login",
													  "usuarios_logados" => array_keys($this->mapSessionConnections),
												  ]));
					}
				}

				$this->mapSessionConnections[$msgArray['id_usuario']] = [
						"foto_usuario" => $msgArray['foto_usuario'],
						"conn" => $from,
					];
				break;

			case "usuario_mensagens":
				if (isset($this->mapSessionConnections[$msgArray['receiver']])){
					$msgArray['foto'] = $this->mapSessionConnections[$msgArray['sender']]['foto_usuario'];
					$this->clients[array_search($this->mapSessionConnections[$msgArray['receiver']]['conn'], $this->clients)]->send(json_encode($msgArray));
				}
				break;

			case "close":
				unset($this->mapSessionConnections[$msgArray['id_usuario']]);

				$logoutMsg = [
					"tipo" => "logout",
					"id_usuario" => $msgArray['id_usuario'],
				];
				foreach($this->clients AS $client) {
					if ($from != $client) {
						$client->send(json_encode($logoutMsg));
					}
				}
				break;
		}
	}

	public function onClose(ConnectionInterface $conn) {
		unset($this->clients[array_search($conn, $this->clients)]);
		//echo "Connection {$conn->resourceId} has disconnected\n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		//echo "An error has occurred: {$e->getMessage()}\n";
		$conn->close();
	}
}