<?php
use Workerman\Bot\Bot;
use Workerman\Bot\Data;
require_once __DIR__ . '/Autoloader.php';

$ws_url = '127.0.0.1';
$ws_port = '8080';

$bot = new Bot('ws://' . $ws_url . ':' . $ws_port);

function onConnectApi()//api系统正常连接后，会触发一次 (类似于插件启用时)
{
	//Data函数的使用应该在连接成功之后，否则机器人无法正常工作
	Data::getLoginInfo();
}

/**
 * 私聊消息事件，每次私聊都会被调用
 *
 * @param array $quick 快速操作-事件数据对象
 * @param int $time 事件发生的时间戳
 * @param int $self_id 收到事件的机器人 QQ 号
 * @param string $post_type 上报类型 (可能是message)
 * @param string $message_type 消息类型 (可能是private)
 * @param string $sub_type 消息子类型，如果是好友则是 friend，如果是群临时会话则是 group (可能是friend、group、other)
 * @param int $message_id 消息 ID
 * @param int $user_id 发送者 QQ 号
 * @param string $message 消息内容
 * @param string $raw_message 原始消息内容
 * @param int $font 字体
 * @param array $sender 发送人信息 (user_id, nickname, card, sex, age, area, level, role, title)
 */
function PrivateMessageEvent($quick, $time, $self_id, $post_type, $message_type, $sub_type, $message_id, $user_id, $message, $raw_message, $font, $sender)
{
	Data::sendPrivateMsg($user_id, $message);
}

/**
 * 群消息事件，每次群聊都会被调用
 *
 * @param array $quick 快速操作-事件数据对象
 * @param int $time 事件发生的时间戳
 * @param int $self_id 收到事件的机器人 QQ 号
 * @param string $post_type 上报类型 (可能是message)
 * @param string $message_type 消息类型 (可能是group)
 * @param string $sub_type 消息子类型，正常消息是 normal，匿名消息是 anonymous，系统提示（如「管理员已禁止群内匿名聊天」）是 notice
 * @param int $message_id 消息 ID
 * @param int $group_id 群号
 * @param int $user_id 发送者 QQ 号
 * @param object $anonymous 匿名信息，如果不是匿名消息则为 null
 * @param string $message 消息内容
 * @param string $raw_message 原始消息内容
 * @param int $font 字体
 * @param array $sender 发送人信息 (user_id, nickname, card, sex, age, area, level, role, title)
 */
function GroupMessageEvent($quick, $time, $self_id, $post_type, $message_type, $sub_type, $message_id, $group_id, $user_id, $anonymous, $message, $raw_message, $font, $sender)
{
	//do some thing
}

function CQGroupAdministratorChangeEvent($time, $self_id, $post_type, $notice_type, $sub_type, $group_id, $user_id)
{
	//do some thing
}

function CQGroupMuteChangeEvent($time, $self_id, $post_type, $notice_type, $sub_type, $group_id, $operator_id, $user_id, $duration)
{
	//do some thing
}

function CQFriendAddEvent($time, $self_id, $post_type, $notice_type, $user_id)
{
	//do some thing
}

function CQFriendRequestEvent($quick, $time, $self_id, $post_type, $request_type, $user_id, $comment, $flag)
{
	Data::setFriendAdd($flag);
}

function CQGroupMemberAddRequestEvent($quick, $time, $self_id, $post_type, $request_type, $sub_type, $group_id, $user_id, $comment, $flag)
{
	//do some thing
}

//API回调参数请访问OneBot标准: https://github.com/howmanybots/onebot/blob/master/v11/specs/api/public.md
function onMessageApi($json)//api回调，每次调用API函数会触发
{
	if($json['status'] == 'ok')
	{
		$data = $json['data'];
		switch($data['ClassType'])
		{
			case 'MessageData'://send_private_msg()回调 \ send_group_msg()回调 \ send_msg()回调
				//do some thing
				break;
			case 'ResponseMessageData'://?
				//Data::sendPrivateMsg(123456789, json_encode($data));
				break;
			case 'GetMessageData'://get_msg()回调
				//do some thing
				break;
			case 'LoginInfoData'://getLoginInfo()回调
				echo "登录QQ账号: {$data['nickname']} ({$data['user_id']})\n";
				//do some thing
				break;
			case 'StrangerInfoData'://get_stranger_info()回调
				//do some thing
				break;
			case 'FriendData'://get_friend_list()回调
				//do some thing
				break;
			case 'GroupData'://get_group_list()回调
				//do some thing
				break;
			case 'GroupInfoData'://get_group_info()回调
				//do some thing
				break;
			case 'MemberInfoData'://get_group_member_info()回调 \ get_group_member_list() List <>回调
				//do some thing
				break;
			case 'ImageInfoData'://get_image()回调
				//do some thing
				break;
			case 'RecordInfoData'://get_record()回调
				//do some thing
				break;
			case 'CanSendImageData'://can_send_image()回调
				//do some thing
				break;
			case 'CanSendRecordData'://can_send_record()回调
				//do some thing
				break;
			case 'PluginStatusData'://get_status() <PluginsGoodData>回调
				//do some thing
				break;
			/*
			case 'PluginsGoodData'://get_status()回调
				//do some thing
				break;
			*/
			case 'VersionInfoData'://get_version_info()回调
				//do some thing
				break;
			case 'HonorInfoData'://get_group_honor_info()回调
				//do some thing
				break;
			default:
				print_r($data);
				break;
		}
	}else{
		echo "";
	}
}

function onCloseApi()//api连接被关闭时，会触发一次 (已自动5秒重连)
{
	//do some thing
}

function onConnectEvent()//event系统正常连接后，会触发一次 (类似于插件启用时)
{
	//不应该在此调用任何函数，因为api系统未确保连接成功
}

function onCloseEvent()//event连接被关闭时，会触发一次 (已自动5秒重连)
{
	//do some thing
}