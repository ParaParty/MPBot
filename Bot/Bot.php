<?php
namespace Workerman\Bot;

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;

Class Bot{
	public $ws_url;
	
	public $api;
	public $event;
	
	//public $echos = array();
	
	public function __construct($ws_url){
		$this->ws_url = $ws_url;

		$worker = new Worker();

		$worker->onWorkerStart = function($worker){
			#---------------------api----------------------
			$this->api = new AsyncTcpConnection($this->ws_url . "/api");
			echo "[api]连接Mirai中...\n";

			$this->api->onConnect = function($connection)
			{
				echo "[api]连接成功，可以开始调用\n";
				new Data($connection);
				onConnectApi();
			};

            $this->api->onMessage = function($connection, $data) {
                onMessageApi(json_decode($data, true));
            };
			
			$this->api->onClose = function($con) {
				echo "[api]失去与主机的连接，5秒后尝试重连...\n";
				$con->reConnect(5);
                onCloseApi();
			};

			$this->api->connect();
			#---------------------event----------------------
			$this->event = new AsyncTcpConnection($this->ws_url . "/event");
			echo "[event]连接Mirai中...\n";

			$this->event->onConnect = function($connection)
			{
				echo "[event]连接成功，开始接收信息\n";
                onConnectEvent();
			};

            $this->event->onMessage = function($connection, $data) {
                onMessageEvent(json_decode($data, true));
            };
			
			$this->event->onClose = function($con) {
				echo "[event]失去与主机的连接，5秒后尝试重连...\n";
				$con->reConnect(5);
                onCloseEvent();
			};

			$this->event->connect();
		};
		Worker::runAll();
	}
}
