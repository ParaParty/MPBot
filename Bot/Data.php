<?php
namespace Workerman\Bot;

use Workerman\Connection\AsyncTcpConnection;

Class Data{
	public static AsyncTcpConnection $connection;
	
	public function __construct($connection){
		self::$connection = $connection;
	}
	
	public static function getData($action, $params = null, $echo = null){
		$data = array();
		$data['action'] = $action;
		if($params) $data['params'] = $params;
		if($echo) $data['echo'] = $echo;
		return json_encode($data);
	}

	##################################信息类###################################
	public static function sendPrivateMsg($qq, $msg, $auto_escape = false)
	{
		self::$connection->send(self::getData("send_private_msg", ['user_id' => $qq, 'message' => $msg, 'auto_escape' => $auto_escape]));
	}
	
	public static function sendGroupMsg($qqq, $msg, $auto_escape = false)
	{
		self::$connection->send(self::getData("send_group_msg", ['group_id' => $qqq, 'message' => $msg, 'auto_escape' => $auto_escape]));
	}

    public static function sendMsg($message_type, $qq, $qqq, $msg, $auto_escape = false)
    {
        self::$connection->send(self::getData("send_msg", ['message_type' => $message_type, 'user_id' => $qq, 'group_id' => $qqq, 'message' => $msg, 'auto_escape' => $auto_escape]));
    }

    public static function deleteMsg($message_id)
    {
        self::$connection->send(self::getData("delete_msg", ['message_id' => $message_id]));
    }

    //TODO 可能需要修改
    public static function getMsg($message_id)
    {
        self::$connection->send(self::getData("get_msg", ['message_id' => $message_id]));
    }

    public static function getForwardMsg($id)
    {
        self::$connection->send(self::getData("get_forward_msg", ['id' => $id]));
    }

    ##################################群组管理类###################################
    public static function setGroupKick($qqq, $qq, $request)
    {
        self::$connection->send(self::getData("set_group_kick", ['group_id' => $qqq, 'user_id' => $qq, 'reject_add_request' => $request]));
    }

    public static function setGroupBan($qqq, $qq, $duration)
    {
        self::$connection->send(self::getData("set_group_ban", ['group_id' => $qqq, 'user_id' => $qq, 'duration' => $duration]));
    }

    //TODO 可能需要修改
    public static function setGroupAKick($qqq, $anonymous, $flag, $duration)
    {
        self::$connection->send(self::getData("set_group_anonymous_ban", ['group_id' => $qqq, 'anonymous' => $anonymous, 'flag' => $flag,'duration' => $duration]));
    }

    public static function setGroupABan($qqq, $enable = true)
    {
        self::$connection->send(self::getData("set_group_whole_ban", ['group_id' => $qqq, 'enable' => $enable]));
    }

    public static function setGroupAdmin($qqq, $qq, $enable = true)
    {
        self::$connection->send(self::getData("set_group_admin", ['group_id' => $qqq, 'user_id' => $qq, 'enable' => $enable]));
    }

    public static function setGroupAnonymous($qqq, $enable = true)
    {
        self::$connection->send(self::getData("set_group_anonymous", ['group_id' => $qqq, 'enable' => $enable]));
    }

    public static function setGroupCard($qqq, $qq, $card = '')
    {
        self::$connection->send(self::getData("set_group_card", ['group_id' => $qqq, 'user_id' => $qq, 'card' => $card]));
    }

    public static function setGroupName($qqq, $name)
    {
        self::$connection->send(self::getData("set_group_name", ['group_id' => $qqq, 'group_name' => $name]));
    }

    public static function setGroupLeave($qqq, $is_dismiss = false)
    {
        self::$connection->send(self::getData("set_group_leave", ['group_id' => $qqq, 'is_dismiss' => $is_dismiss]));
    }

    public static function setGroupTitle($qqq, $qq, $title = '', $duration = -1)
    {
        self::$connection->send(self::getData("set_group_special_title", ['group_id' => $qqq, 'user_id' => $qq, 'special_title' => $title, 'duration' => $duration]));
    }

    ##################################杂项类###################################
    public static function sendLike($qq, $times)
    {
        self::$connection->send(self::getData("send_like", ['user_id' => $qq, 'times' => $times]));
    }

    public static function setFriendAdd($flag, $approve, $remark)
    {
        self::$connection->send(self::getData("set_friend_add_request", ['flag' => $flag, 'approve' => $approve, 'remark' => $remark]));
    }

    public static function setGroupAdd($flag, $type, $approve, $reason)
    {
        self::$connection->send(self::getData("set_group_add_request", ['flag' => $flag, 'type' => $type, '$approve' => $approve, 'reason' => $reason]));
    }

    public static function getLoginInfo()
    {
        self::$connection->send(self::getData("get_login_info"));
    }

    public static function getStrangerInfo($qq, $no_cache)
    {
        self::$connection->send(self::getData("get_stranger_info", ['user_id' => $qq, 'no_cache' => $no_cache]));
    }

    public static function getFriendList()
    {
        self::$connection->send(self::getData("get_friend_list"));
    }

    public static function getFriendInfo($qqq, $no_cache)
    {
        self::$connection->send(self::getData("get_group_info", ['group_id' => $qqq, 'no_cache' => $no_cache]));
    }

    public static function getGroupList()
    {
        self::$connection->send(self::getData("get_group_list"));
    }

    public static function getGroupMemberInfo($qqq, $qq, $no_cache)
    {
        self::$connection->send(self::getData("get_group_member_info", ['group_id' => $qqq, 'user_id' => $qq, 'no_cache' => $no_cache]));
    }

    public static function getGroupMemberList($qqq)
    {
        self::$connection->send(self::getData("get_group_member_list", ['group_id' => $qqq]));
    }

    public static function getGroupHonorInfo($qqq, $type)
    {
        self::$connection->send(self::getData("get_group_honor_info", ['group_id' => $qqq, 'type' => $type]));
    }

    public static function getCookies($domain)
    {
        self::$connection->send(self::getData("get_cookies", ['domain' => $domain]));
    }

    public static function getCsrfToken($domain)
    {
        self::$connection->send(self::getData("get_csrf_token", ['domain' => $domain]));
    }

    public static function getCredentials($domain)
    {
        self::$connection->send(self::getData("get_credentials", ['domain' => $domain]));
    }

    public static function getRecord($file, $out_format)
    {
        self::$connection->send(self::getData("get_record", ['file' => $file, 'out_format' => $out_format]));
    }

    public static function getImage($file)
    {
        self::$connection->send(self::getData("get_image", ['dfile' => $file]));
    }

    public static function canSendImage()
    {
        self::$connection->send(self::getData("can_send_image"));
    }

    public static function canSendRecord()
    {
        self::$connection->send(self::getData("can_send_record"));
    }

    public static function getStatus()
    {
        self::$connection->send(self::getData("get_status"));
    }

    public static function getVersionInfo()
    {
        self::$connection->send(self::getData("get_version_info"));
    }

    public static function setRestart()
    {
        self::$connection->send(self::getData("set_restart"));
    }

    public static function cleanCache()
    {
        self::$connection->send(self::getData("clean_cache"));
    }
}
