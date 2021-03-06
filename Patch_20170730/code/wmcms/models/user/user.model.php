<?php
/**
* 用户模型
*
* @version        $Id: user.model.php 2016年5月27日 11:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class UserModel
{
	public $table = '@user_user';
	public $apiTable = '@user_apilogin';
	//用户头像
	public $head;
	//邮箱验证状态
	public $emailTrue;
	//用户密码
	public $psw;
	//用户数据
	public $data;
	
	
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 新增用户
	 */
	function Add()
	{
		$this->data['user_regtime'] = time();
		$this->data['user_logintime'] = 0;
		return wmsql::Insert($this->table, $this->data);
	}
	
	
	/**
	 * 用户登录
	 * @param 参数1，选填，登录的奖励数组
	 */
	function EveryDayLogin( $userId , $rewardData = '' )
	{
		$log['module'] = 'user';
		$log['type'] = 'login';
		$log['gold1'] = $rewardData['gold1'];
		$log['gold2'] = $rewardData['gold2'];
		$log['remark'] = '每日登录赠送！';

		//资金变更
		$this->CapitalChange( $userId , $log , $rewardData['gold1'] , $rewardData['gold2']);
		
		//修改登录时间
		$data['user_logintime'] = time();
		return $this->UpdateExp($userId, $rewardData['exp'] , $data);
	}
	
	
	/**
	 * 更新用户经验值
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，变更的经验值
	 * @param 参数3，选填，其他的条件
	 */
	function UpdateExp($uid , $exp , $data = '')
	{
		if( $exp != '0' && !empty($data) )
		{
			$lvArr = user::GetLV();
			if( $exp > 0 && $lvArr['is_max'] != '1')
			{
				$data['user_exp'] = array( '+' , $exp );
			}
			if( is_array($data) )
			{
				wmsql::Update($this->table, $data, 'user_id='.$uid);
			}
		}
		return true;
	}
	
	/**
	 * 更新用户奖励
	 * @param 参数1，必须，奖励金币1
	 * @param 参数2，必须，奖励金币2
	 * @param 参数3，必须，奖励经验
	 * @param 参数4，选填，奖励备注
	 */
	function RewardUpdate( $uid , $rewardData , $log , $type = '1')
	{
		if( !isset($rewardData['gold1']) )
		{
			$rewardData['gold1'] = '0';
		}
		if( !isset($rewardData['gold2']) )
		{
			$rewardData['gold2'] = '0';
		}
		if( !isset($rewardData['exp']) )
		{
			$rewardData['exp'] = '0';
		}
		
		$log['gold1'] = $rewardData['gold1'];
		$log['gold2'] = $rewardData['gold2'];
		
		//资金变更
		$this->CapitalChange( $uid , $log , $rewardData['gold1'] , $rewardData['gold2'] , $type );
		
		return $this->UpdateExp($uid, $rewardData['exp']);
	}
	
	
	/**
	 * 用户资金变更记录
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，资金变更日志
	 * @param 参数3，必须，变更的金币1
	 * @param 参数4，必须，变更的金币2
	 * @param 参数5，选填，变更类型，是加还是减
	 */
	function CapitalChange( $uid , $log , $gold1='0' , $gold2='0' , $type = '1' )
	{
		if( $gold1 == '0' && $gold2 == '0' )
		{
			return true;
		}
		else
		{
			$operator = '+';
			if( $type == '2' )
			{
				$operator = '-';
			}
	
			//变更用户资金
			$data['user_gold1'] = array( $operator , $gold1 );
			$data['user_gold2'] = array( $operator , $gold2 );
			wmsql::Update( $this->table , $data , 'user_id='.$uid );
			
			//插入用户资金变更记录
			$logData['log_status'] = $type;
			$logData['log_module'] = $log['module'];
			$logData['log_type'] = $log['type'];
			$logData['log_user_id'] = $uid;
			if( !isset($log['tuid']) )
			{
				$log['tuid'] = '0';
			}
			if( !isset($log['cid']) )
			{
				$log['cid'] = '0';
			}
			$logData['log_tuser_id'] = $log['tuid'];
			$logData['log_cid'] = $log['cid'];
			$logData['log_gold1'] = $gold1;
			$logData['log_gold2'] = $gold2;
			$logData['log_remark'] = $log['remark'];
			$financeMod = NewModel('user.finance_log');
			return $financeMod->Insert($logData);
		}
	}
	
	
	/**
	 * 检查数据条数
	 * @param 参数1，必须，条件
	 */
	function GetCount( $where )
	{
		$where['where']= $where;
		$where['table'] = $this->table;
		$count = wmsql::GetCount( $where , 'user_id');
		return $count;
	}


	/**
	 * 检查数据条数
	 * @param 参数1，必须，条件，数组或者id
	 */
	function GetOne( $wheresql )
	{
		if( is_array($wheresql) )
		{
			$where['where']= $wheresql;
		}
		else
		{
			$where['where']['user_id'] = $wheresql;
		}
		$where['table'] = $this->table;
		$data = wmsql::GetOne( $where );
		return $data;
	}
	/**
	 * 根据用户账号查询用户数据
	 * @param 参数1，必须，用户账号
	 */
	function GetByName($name)
	{
		$where['user_name'] = $name;
		return $this->GetOne($where);
	}
	
	
	/**
	 * 保存用户属性
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，选填，用户id
	 */
	function Save( $data , $uid = '')
	{
		if( $uid == '' )
		{
			$uid = user::GetUid();
		}
		return wmsql::Update($this->table, $data , 'user_id='.$uid);
	}
	/**
	 * 保存用户头像
	 */
	function SaveHead($uid = '')
	{
		$data['user_head'] = $this->head;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户邮箱验证状态
	 */
	function SaveEmailTrue($uid = '')
	{
		$data['user_emailtrue'] = $this->emailTrue;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户密码
	 */
	function SavePsw($uid = '')
	{
		$data['user_psw'] = $this->psw;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户账号状态
	 */
	function SaveDisplay($uid = '')
	{
		$data['user_display'] = 1;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户是否首充
	 */
	function SaveCharge($uid='')
	{
		$data['user_ischarge'] = 1;
		return $this->Save( $data , $uid);
	}
	
	
	/**
	 * 获得用户的api登录信息
	 * @param 参数1，必须，登录类型
	 * @param 参数2，必须，openid
	 * @param 参数3，选填，用户id
	 */
	function GetApiLogin($loginType , $openid , $uid = '')
	{
		$where['table'] = $this->apiTable;
		$where['where']['api_type'] = $loginType;
		$where['where']['api_openid'] = $openid;
		if( $uid != '' )
		{
			$where['where']['api_uid'] = $uid;
		}
		return wmsql::GetOne($where);
	}
	
	/**
	 * 删除指定用户的所有api登录信息
	 * @param 参数1，必须，用户id
	 */
	function DelApiLogin($uid)
	{
		return wmsql::Delete($this->apiTable , array('api_uid'=>$uid));
	}
	
	/**
	 * 插入用户的接口登录绑定信息
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，第三方登录类型
	 * @param 参数3，必须，第三方公共id
	 */
	function InsertApiLogin($uid , $type , $openid)
	{
		if( $this->GetApiLogin($type, $openid , $uid) )
		{
			return false;
		}
		else
		{
			$data['api_uid'] = $uid;
			$data['api_type'] = $type;
			$data['api_openid'] = $openid;
			return WMSql::Insert($this->apiTable, $data);
		}
	}
}
?>