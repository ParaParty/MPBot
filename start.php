<?php
use Workerman\Bot\Bot;
use Workerman\Bot\Data;
require_once __DIR__ . '/Autoloader.php';

$ws_url = '127.0.0.1';
$ws_port = '8080';

$bot = new Bot('ws://' . $ws_url . ':' . $ws_port);

function onConnectApi()//api系统正常连接后，会触发一次 (类似于插件启用时)
{
    //Data函数的使用应该在连接成功之后，否则机器人无法接收
    Data::getLoginInfo();
}

//API回调参数请访问OneBot标准: https://github.com/howmanybots/onebot/blob/master/v11/specs/api/public.md
function onMessageApi($json)//api回调，每次调用API函数会触发
{
	if($json['status'] == 'ok')
	{
		$data = $json['data'];
		switch($data['ClassType'])
		{
		    case 'MessageData'://send_private_msg() \ send_group_msg() \ send_msg()
                //do some thing
                break;
            case 'ResponseMessageData'://?
                Data::sendPrivateMsg(345793738, json_encode($data));
                break;
            case 'GetMessageData'://get_msg()
                //do some thing
                break;
            case 'LoginInfoData'://getLoginInfo()回调
                echo "登录QQ账号: {$data['nickname']} ({$data['user_id']})\n";
                //do some thing
                break;
            case 'StrangerInfoData'://get_stranger_info()
                //do some thing
                break;
            case 'FriendData'://get_friend_list()
                //do some thing
                break;
            case 'GroupData'://get_group_list()
                //do some thing
                break;
            case 'GroupInfoData'://get_group_info()
                //do some thing
                break;
            case 'MemberInfoData'://get_group_member_info() \ get_group_member_list() List <>
                //do some thing
                break;
            case 'ImageInfoData'://get_image()
                //do some thing
                break;
            case 'RecordInfoData'://get_record()
                //do some thing
                break;
            case 'CanSendImageData'://can_send_image()
                //do some thing
                break;
            case 'CanSendRecordData'://can_send_record()
                //do some thing
                break;
            case 'PluginStatusData'://get_status() <PluginsGoodData>
                //do some thing
                break;
            /*
            case 'PluginsGoodData'://get_status()
                //do some thing
                break;
            */
            case 'VersionInfoData'://get_version_info()
                //do some thing
                break;
            case 'HonorInfoData'://get_group_honor_info()
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

//信息回调参数请访问OneBot标准: https://github.com/howmanybots/onebot/blob/master/v11/specs/event/README.md
function onMessageEvent($data)//event回调，当有事件时会触发
{
	switch($data['ClassType'])
	{
        case 'CQLifecycleMetaEvent'://ws生命周期
            //do some thing
            break;
        case 'CQHeartbeatMetaEvent'://ws心跳
            //do some thing
            break;
        case 'PrivateMessage'://私聊信息
            Data::sendPrivateMsg($data['user_id'], $data['message']);
            break;
        case 'GroupMessage'://群聊信息
            //do some thing
            break;
        case 'CQGroupAdministratorChangeEvent'://群管理员变动
            //do some thing
            break;
        case 'CQGroupMuteChangeEvent'://群禁言
            //do some thing
            break;
        case 'CQFriendAddEvent'://好友添加
            //do some thing
            break;
        case 'CQFriendRequestEvent'://加好友请求
            //do some thing
            break;
        case 'CQGroupMemberAddRequestEvent'://加群请求／邀请
            //do some thing
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
}

function onCloseEvent()//event连接被关闭时，会触发一次 (已自动5秒重连)
{
    //do some thing
}