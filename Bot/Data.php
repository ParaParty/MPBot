<?php
namespace Workerman\Bot;

Class Data{
	public static $connection;
	
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
	/**
	 * 发送私聊消息
	 *
	 * @param int $user_id QQ号
	 * @param string $msg 要发送的内容
	 * @param boolean $auto_escape 消息内容是否作为纯文本发送（即不解析 CQ 码），只在 message 字段是字符串时有效
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去 
	 */
	public static function sendPrivateMsg($user_id, $msg, $auto_escape = false)
	{
		return self::$connection->send(self::getData("send_private_msg", ['user_id' => $user_id, 'message' => $msg, 'auto_escape' => $auto_escape]));
	}

	/**
	 * 发送群消息
	 *
	 * @param int $group_id 群号
	 * @param string $msg 要发送的内容
	 * @param boolean $auto_escape 消息内容是否作为纯文本发送（即不解析 CQ 码），只在 message 字段是字符串时有效
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function sendGroupMsg($group_id, $msg, $auto_escape = false)
	{
		return self::$connection->send(self::getData("send_group_msg", ['group_id' => $group_id, 'message' => $msg, 'auto_escape' => $auto_escape]));
	}

	//TODO 可能需要修改

	/**
	 * 发送消息
	 *
	 * @param $message_type
	 * @param int $user_id QQ号
	 * @param int $group_id 群号
	 * @param string $msg 要发送的内容
	 * @param boolean $auto_escape 消息内容是否作为纯文本发送（即不解析 CQ 码），只在 message 字段是字符串时有效
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function sendMsg($message_type, $user_id, $group_id, $msg, $auto_escape = false)
	{
		return self::$connection->send(self::getData("send_msg", ['message_type' => $message_type, 'user_id' => $user_id, 'group_id' => $group_id, 'message' => $msg, 'auto_escape' => $auto_escape]));
	}

	/**
	 * 撤回消息
	 *
	 * @param int $message_id 消息 ID
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function deleteMsg($message_id)
	{
		return self::$connection->send(self::getData("delete_msg", ['message_id' => $message_id]));
	}

	//TODO 可能需要修改
	/**
	 * 获取消息
	 *
	 * @param int $message_id 消息 ID
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function getMsg($message_id)
	{
		return self::$connection->send(self::getData("get_msg", ['message_id' => $message_id]));
	}

	/**
	 * 获取合并转发消息
	 *
	 * @param int $id 合并转发 ID
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function getForwardMsg($id)
	{
		return self::$connection->send(self::getData("get_forward_msg", ['id' => $id]));
	}

	##################################群组管理类###################################
	/**
	 * 群组踢人
	 *
	 * @param int $group_id 群号
	 * @param int $user_id 要踢的 QQ 号
	 * @param boolean $request 拒绝此人的加群请求
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function setGroupKick($group_id, $user_id, $request = false)
	{
		return self::$connection->send(self::getData("set_group_kick", ['group_id' => $group_id, 'user_id' => $user_id, 'reject_add_request' => $request]));
	}

	/**
	 * 群组单人禁言
	 *
	 * @param int $group_id 群号
	 * @param int $user_id 要踢的 QQ 号
	 * @param boolean $duration 禁言时长，单位秒，0 表示取消禁言
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function setGroupBan($group_id, $user_id, $duration = 1800)
	{
		return self::$connection->send(self::getData("set_group_ban", ['group_id' => $group_id, 'user_id' => $user_id, 'duration' => $duration]));
	}

	//TODO 可能需要修改
	/**
	 * 群组匿名用户禁言
	 *
	 * @param int $group_id 群号
	 * @param object $anonymous 可选，要禁言的匿名用户对象（群消息上报的 anonymous 字段）
	 * @param string $flag 可选，要禁言的匿名用户的 flag（需从群消息上报的数据中获得）
	 * @param boolean $duration 禁言时长，单位秒，无法取消匿名用户禁言
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function setGroupABan($group_id, $anonymous, $flag, $duration = 1800)
	{
		return self::$connection->send(self::getData("set_group_anonymous_ban", ['group_id' => $group_id, 'anonymous' => $anonymous, 'flag' => $flag,'duration' => $duration]));
	}

	/**
	 * 群组全员禁言
	 *
	 * @param int $group_id 群号
	 * @param boolean $enable 是否禁言
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function setGroupAllBan($group_id, $enable = true)
	{
		return self::$connection->send(self::getData("set_group_whole_ban", ['group_id' => $group_id, 'enable' => $enable]));
	}

	/**
	 * 群组设置管理员
	 *
	 * @param int $group_id 群号
	 * @param int $user_id 要踢的 QQ 号
	 * @param boolean $enable true 为设置，false 为取消
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function setGroupAdmin($group_id, $user_id, $enable = true)
	{
		return self::$connection->send(self::getData("set_group_admin", ['group_id' => $group_id, 'user_id' => $user_id, 'enable' => $enable]));
	}

    /**
     * 群组匿名
     *
     * @param int $group_id 群号
     * @param boolean $enable true 为设置，false 为取消
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupAnonymous($group_id, $enable = true)
	{
		return self::$connection->send(self::getData("set_group_anonymous", ['group_id' => $group_id, 'enable' => $enable]));
	}

    /**
     * 设置群名片（群备注）
     *
     * @param int $group_id 群号
     * @param int $user_id 要设置的 QQ 号
     * @param string $card 群名片内容，不填或空字符串表示删除群名片
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupCard($group_id, $user_id, $card = '')
	{
		return self::$connection->send(self::getData("set_group_card", ['group_id' => $group_id, 'user_id' => $user_id, 'card' => $card]));
	}

    /**
     * 设置群名
     *
     * @param int $group_id 群号
     * @param string $group_name 新群名
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupName($group_id, $group_name)
	{
		return self::$connection->send(self::getData("set_group_name", ['group_id' => $group_id, 'group_name' => $group_name]));
	}

    /**
     * 退出群组
     *
     * @param int $group_id 群号
     * @param boolean $is_dismiss 是否解散，如果登录号是群主，则仅在此项为 true 时能够解散
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupLeave($group_id, $is_dismiss = false)
	{
		return self::$connection->send(self::getData("set_group_leave", ['group_id' => $group_id, 'is_dismiss' => $is_dismiss]));
	}

    /**
     * 设置群组专属头衔
     *
     * @param int $group_id 群号
     * @param int $user_id 要设置的 QQ 号
     * @param string $special_title 专属头衔，不填或空字符串表示删除专属头衔
     * @param int $duration 专属头衔有效期，单位秒，-1 表示永久，不过此项似乎没有效果，可能是只有某些特殊的时间长度有效，有待测试
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupTitle($group_id, $user_id, $special_title = '', $duration = -1)
	{
		return self::$connection->send(self::getData("set_group_special_title", ['group_id' => $group_id, 'user_id' => $user_id, 'special_title' => $special_title, 'duration' => $duration]));
	}

	##################################杂项类###################################
    /**
     * 发送好友赞
     *
     * @param int $user_id 要设置的 QQ 号
     * @param string $times 赞的次数，每个好友每天最多 10 次
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function sendLike($user_id, $times = 1)
	{
		return self::$connection->send(self::getData("send_like", ['user_id' => $user_id, 'times' => $times]));
	}

    /**
     * 处理加好友请求
     *
     * @param string $flag 加好友请求的 flag（需从上报的数据中获得）
     * @param int $approve 是否同意请求
     * @param string $remark 添加后的好友备注（仅在同意时有效）
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setFriendAdd($flag, $approve = true, $remark = "")
	{
		return self::$connection->send(self::getData("set_friend_add_request", ['flag' => $flag, 'approve' => $approve, 'remark' => $remark]));
	}

    /**
     * 处理加群请求／邀请
     *
     * @param string $flag 加好友请求的 flag（需从上报的数据中获得）
     * @param string $type add 或 invite，请求类型（需要和上报消息中的 sub_type 字段相符）
     * @param int $approve 是否同意请求
     * @param string $reason 拒绝理由（仅在拒绝时有效）
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setGroupAdd($flag, $type, $approve = true, $reason = "")
	{
		return self::$connection->send(self::getData("set_group_add_request", ['flag' => $flag, 'type' => $type, '$approve' => $approve, 'reason' => $reason]));
	}

    /**
     * 获取登录号信息
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getLoginInfo()
	{
		return self::$connection->send(self::getData("get_login_info"));
	}

    /**
     * 获取陌生人信息
     *
     * @param int $user_id QQ 号
     * @param boolean $no_cache 是否不使用缓存（使用缓存可能更新不及时，但响应更快）
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getStrangerInfo($user_id, $no_cache = false)
	{
		return self::$connection->send(self::getData("get_stranger_info", ['user_id' => $user_id, 'no_cache' => $no_cache]));
	}

    /**
     * 获取好友列表
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getFriendList()
	{
		return self::$connection->send(self::getData("get_friend_list"));
	}

    /**
     * 获取群信息
     *
     * @param int $group_id 群号
     * @param boolean $no_cache 是否不使用缓存（使用缓存可能更新不及时，但响应更快）
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getFriendInfo($group_id, $no_cache = false)
	{
		return self::$connection->send(self::getData("get_group_info", ['group_id' => $group_id, 'no_cache' => $no_cache]));
	}

    /**
     * 获取群列表
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getGroupList()
	{
		return self::$connection->send(self::getData("get_group_list"));
	}

    /**
     * 获取群成员信息
     *
     * @param int $group_id 群号
     * @param int $user_id QQ 号
     * @param boolean $no_cache 是否不使用缓存（使用缓存可能更新不及时，但响应更快）
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getGroupMemberInfo($group_id, $user_id, $no_cache = false)
	{
		return self::$connection->send(self::getData("get_group_member_info", ['group_id' => $group_id, 'user_id' => $user_id, 'no_cache' => $no_cache]));
	}

    /**
     * 获取群成员列表
     *
     * @param int $group_id 群号
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getGroupMemberList($group_id)
	{
		return self::$connection->send(self::getData("get_group_member_list", ['group_id' => $group_id]));
	}

    /**
     * 获取群荣誉信息
     *
     * @param int $group_id 群号
     * @param string $type 要获取的群荣誉类型，可传入 talkative performer legend strong_newbie emotion 以分别获取单个类型的群荣誉数据，或传入 all 获取所有数据
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getGroupHonorInfo($group_id, $type)
	{
		return self::$connection->send(self::getData("get_group_honor_info", ['group_id' => $group_id, 'type' => $type]));
	}

    /**
     * 获取 Cookies
     *
     * @param string $domain 需要获取 cookies 的域名
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getCookies($domain = '')
	{
		return self::$connection->send(self::getData("get_cookies", ['domain' => $domain]));
	}

    /**
     * 获取 CSRF Token
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getCsrfToken($domain)
	{
		return self::$connection->send(self::getData("get_csrf_token", ['domain' => $domain]));
	}

    /**
     * 获取 QQ 相关接口凭证
     *
     * @param string $domain 需要获取 cookies 的域名
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getCredentials($domain = '')
	{
		return self::$connection->send(self::getData("get_credentials", ['domain' => $domain]));
	}

    /**
     * 获取语音
     *
     * @param string $file 收到的语音文件名（消息段的 file 参数），如 0B38145AA44505000B38145AA4450500.silk
     * @param string $out_format 要转换到的格式，目前支持 mp3、amr、wma、m4a、spx、ogg、wav、flac
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getRecord($file, $out_format)
	{
		return self::$connection->send(self::getData("get_record", ['file' => $file, 'out_format' => $out_format]));
	}

    /**
     * 获取图片
     *
     * @param string $file 收到的图片文件名（消息段的 file 参数），如 6B4DE3DFD1BD271E3297859D41C530F5.jpg
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getImage($file)
	{
		return self::$connection->send(self::getData("get_image", ['dfile' => $file]));
	}

    /**
     * 检查是否可以发送图片
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function canSendImage()
	{
		return self::$connection->send(self::getData("can_send_image"));
	}

    /**
     * 检查是否可以发送语音
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function canSendRecord()
	{
		return self::$connection->send(self::getData("can_send_record"));
	}

    /**
     * 获取运行状态
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getStatus()
	{
		return self::$connection->send(self::getData("get_status"));
	}

    /**
     * 获取版本信息
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function getVersionInfo()
	{
		return self::$connection->send(self::getData("get_version_info"));
	}

    /**
     * 重启 OneBot 实现
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function setRestart()
	{
		return self::$connection->send(self::getData("set_restart"));
	}

    /**
     * 清理缓存
     *
     * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
     */
	public static function cleanCache()
	{
		return self::$connection->send(self::getData("clean_cache"));
	}

	/**
	 * 快速操作
	 *
	 * @param array $context 事件数据对象，可做精简，如去掉 message 等无用字段
	 * @param array $operation 快速操作对象，例如 ["ban" => true, "reply" => "请不要说脏话"]
	 * @return mixed (true|null|false)只要不返回false并且网络没有断开，而且服务端接收正常，数据基本上可以看做100%能发过去
	 */
	public static function quickOperation($context, $operation)
	{
		return self::$connection->send(self::getData(".handle_quick_operation", ["context" => $context, "operation" => $operation]));
	}
}
