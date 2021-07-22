<?php
namespace Workerman\Bot;

use Workerman\Lib\Timer;
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
				$connection->ping = Timer::add(30, function($connection)
				{
					Data::getStatus();
				});
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
				$connection->ping = Timer::add(30, function($connection)
				{
					Data::getStatus();
				});
                onConnectEvent();
			};

			//信息回调参数请访问OneBot标准: https://github.com/howmanybots/onebot/blob/master/v11/specs/event/README.md
            $this->event->onMessage = function($connection, $data) {
				$data = json_decode($data, true);
				switch($data['ClassType'])
				{
					case 'CQLifecycleMetaEvent'://ws生命周期
						//do some thing
						break;
					case 'CQHeartbeatMetaEvent'://ws心跳
						//do some thing
						break;
					case 'PrivateMessage'://私聊信息
						PrivateMessageEvent($data, $data['time'], $data['self_id'], $data['post_type'], $data['message_type'],
							$data['sub_type'], $data['message_id'], $data['user_id'], $data['message'], $data['raw_message'],
							$data['font'], $data['sender']);
						break;
					case 'GroupMessage'://群聊信息
						GroupMessageEvent($data, $data['time'], $data['self_id'], $data['post_type'], $data['message_type'],
							$data['sub_type'], $data['message_id'], $data['group_id'], $data['user_id'], $data['anonymous'],
							$data['message'], $data['raw_message'], $data['font'], $data['sender']);
						break;
					case 'CQGroupAdministratorChangeEvent'://群管理员变动
						CQGroupAdministratorChangeEvent($data['time'], $data['self_id'], $data['post_type'], $data['notice_type'],
							$data['sub_type'], $data['group_id'], $data['user_id']);
						break;
					case 'CQGroupMuteChangeEvent'://群禁言
						CQGroupMuteChangeEvent($data['time'], $data['self_id'], $data['post_type'], $data['notice_type'],
							$data['sub_type'], $data['operator_id'], $data['group_id'], $data['user_id'], $data['duration']);
						break;
					case 'CQFriendAddEvent'://好友添加
						CQFriendAddEvent($data['time'], $data['self_id'], $data['post_type'], $data['notice_type'],
							$data['user_id']);
						break;
					case 'CQFriendRequestEvent'://加好友请求
						CQFriendRequestEvent($data, $data['time'], $data['self_id'], $data['post_type'], $data['request_type'],
							$data['user_id'], $data['comment'], $data['flag']);
						break;
					case 'CQGroupMemberAddRequestEvent'://加群请求／邀请
						CQGroupMemberAddRequestEvent($data, $data['time'], $data['self_id'], $data['post_type'], $data['request_type'],
							$data['sub_type'], $data['group_id'], $data['user_id'], $data['comment'], $data['flag']);
						break;
					case 'CQGroupMemberNudgedEvent'://群内戳一戳
						//do some thing
						break;
					case 'CQGroupMessageRecallEvent'://群消息撤回
						//do some thing
						break;
					case 'CQFriendMessageRecallEvent'://好友消息撤回
						//do some thing
						break;
					case 'CQMemberHonorChangeEvent'://群成员荣誉变更
						//do some thing
						break;
					case 'CQMemberJoinEvent'://群成员减少
						//do some thing
						break;
					case 'CQMemberLeaveEvent'://群成员增加
						//do some thing
						break;
					default:
						print_r($data);
						break;
				}
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
