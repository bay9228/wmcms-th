<?php
/**
* 用户登录请求处理
*
* @version        $Id: login.php 2016年5月29日 10:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2018年3月25日 20:23  weimeng
*
*/
if( Session('apilogin_openid') == '' || Session('apilogin_type') == '' )
{
	FormTokenCheck();
	FormCodeCheck('code_user_login');
}

//判断是否开启登录
str::EQ( $userConfig['login_open'], 0, $lang['user']['login_close']);

//接受参数
$name = str::IsEmpty( Post('name') , $lang['user']['name_no'] );
$psw = str::IsEmpty( Post('psw') , $lang['user']['psw_no'] );
$remember = str::IsTrue( Post('remember') , 1);
$time = str::Int( Post('time') );
$apiLogin = str::Int( Post('apilogin'));

//验证参数
//账号长度和账号格式
str::CheckLen( $name , '4,16' , $lang['user']['name_len']  );
str::LN( $name, $lang['user']['name_err'] );
//密码长度和密码格式
str::CheckLen( $psw , '6,16' , $lang['user']['psw_len']  );
str::NCN( $psw, $lang['user']['psw_err'] );
$psw = str::E($psw);



//new一个登录日志模型
$logMod = NewModel('user.log');
$loginCount = $logMod->GetCount();
//检查是否达到最高错误次数
if( $loginCount >= $C['config']['web']['user_login_error_number'] )
{
	$info = tpl::Rep( array('封锁时间'=>$C['config']['web']['user_login_error_time']) ,$lang['user']['login_err'] );
	ReturnData( $info );
}

//查询账号
$where['user_name'] = $name;
$where['user_type'] = 'default';
$data = $userMod->GetOne($where);
//设置用户的数据
user::$user = $data;

//账号存在，密码正确
if ( $data && $data['user_psw'] == $psw )
{
	//审核中
	if( $data['user_status'] == '0' )
	{
		ReturnData( $lang['user']['account_status_0'] );
	}
	//全站封禁
	else if( $data['user_display'] == '0' )
	{
		ReturnData( $lang['user']['account_display_0'] );
	}
	//限时封禁
	else if( $data['user_display'] == '2' )
	{
		//正在封禁的时间段内
		if ( $data['user_displaytime'] > time() )
		{
			$info = tpl::Rep( array('时间'=>date('Y-m-d H:i:s',$data['user_displaytime'])) ,$lang['user']['account_display_2'] );
			ReturnData( $info );
		}
		//封禁的时间小于当前时间就解禁
		else
		{
			$userMod->SaveDisplay();
		}
	}
	
	
	//是否记住登录
	if ( !$remember )
	{
		$time = '';
	}
	//写入登录属性
	Cookie('user_account' , str::A($name, $psw) , $time );


	//是今日首次登录就进行奖励
	$rewardData = '';
	if ( $data['user_logintime'] < strtotime('today') )
	{
		//修改登录时间和赠送的道具
		$rewardData['gold1'] = $userConfig['login_gold1'];
		$rewardData['gold2'] = $userConfig['login_gold2'];
		$rewardData['exp'] = $userConfig['login_exp'];
		$result = $userMod->EveryDayLogin( $data['user_id'] , $rewardData );
		
		//更新推荐票
		$ticketMod = NewModel('user.ticket');
		$lvData = user::GetLV();
		$ticketData['rec'] = $lvData['level_rec'] + $userConfig['login_rec'];
		$ticketData['month'] = $lvData['level_month'] + $userConfig['login_month'];
		$ticketData['remark'] =  $lang['user']['ticket_login_remark'];
		$ticketMod->Update( $data['user_id'] , $ticketData);
	}
	
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,1,$data['user_id']);
	}
	
	//如果是接口登录就绑定账号
	if( $apiLogin == 1 )
	{
		$apiLoginOpenid = Session('apilogin_openid');
		$apiLoginType = Session('apilogin_type');
		Cookie('api_login_type' , 'delete');
		Cookie('api_login_domain' , 'delete');
		Cookie('apilogin_type_name' , 'delete');
		Session('apilogin_type' , 'delete');
		Session('apilogin_openid' , 'delete');
		Session('apilogin_nickname' , 'delete');
		if( $apiLoginOpenid == '' || $apiLoginType == '' )
		{
			ReturnData( $lang['user']['api_err'] );
		}
		else
		{
			$userMod = NewModel('user.user');
			$userMod->InsertApiLogin($data['user_id'] , $apiLoginType , $apiLoginOpenid);
		}
	}
	
	//返回提示
	$info = GetInfo($lang['user']['operate']['login'] , 'user_home');
	$code = 200;

	//表单token删除
	FormDel();
	ReturnData( $info , $ajax , $code );

}
//账号存在，密码错误
else if ( $data )
{
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,2,$data['user_id'],$lang['user']['logpsw_err']);
	}
	
	ReturnData( $lang['user']['psw_exist'] );
}
//账号不存在
else
{
	//开启了插入登录记录
	if ( $userConfig['login_log'] == '1' )
	{
		$logMod->Insert(1,0,0,$lang['user']['account_exist']);
	}
	ReturnData( $lang['user']['account_exist'] );
}
?>