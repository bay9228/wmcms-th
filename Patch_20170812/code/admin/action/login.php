<?php
/**
* 后台登录处理文件
*
* @version        $Id: login.php 2016年3月23日 11:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$code = 300;
if( FormTokenCheck(true) == false )
{
	$tishi = '表单token错误！';
}
else if ( FormCodeCheck('code_admin_login' , true) == false )
{
	$tishi = '对不起，验证码错误！';
}
else
{
	$userName = Post('name');
	$passWord = Post('psw');
	$imgCode = Post('code');
	
	//账号密码检测
	if( trim($userName) == '' || trim($passWord) == '' )
	{
		$tishi = '对不起，账号密码不能为空!';
	}
	else if( str::StrLen($imgCode) <> 4 && C('config.web.admin_login_code') =='1' )
	{
		$tishi = str::StrLen($imgCode).$imgCode.'对不起，验证码格式错误!';
	}
	else if( $imgCode <> strtolower(Session('imgCode')) && C('config.web.admin_login_code') == '1' )
	{
		$tishi = '对不起，验证码错误!';
	}
	else
	{
		$wheresql['table'] = '@manager_manager';
		$wheresql['where']['manager_name'] = $userName;
		$arr = wmsql::GetOne($wheresql);
	
		if( !$arr )
		{
			$tishi = '管理员账号不存在！';
		}
		else
		{
			//浏览器信息检测
			$ua = NewClass('client');
			$uaArr = $ua->Get_Useragent();
			$userAgent = @$_SERVER['HTTP_USER_AGENT'];
			$browser = @$uaArr['1'];
			if( $userAgent == '' )
			{
				$userAgent = '未知UA';
			}
			if( $browser == '' )
			{
				$browser = '未知浏览器';
			}
			$log['manager_id'] = $arr['manager_id'];
			$log['login_time'] = time();
			$log['login_status'] = '1';
			$log['login_ip'] = GetIp();
			$log['login_ua'] = $userAgent;
			$log['login_browser'] = $browser;
			
			//密码错误
			if( $arr['manager_psw'] != str::E($passWord) )
			{
				$tishi = '密码错误！';
				//写入错误日志
				$log['login_status'] = '2';
				wmsql::Insert('@manager_login', $log);
			}
			//封禁的账号
			else if( $arr['manager_status'] == 0 )
			{
				$tishi="对不起,您的账号已经被禁用！如有疑问请联系超级管理员！";
			}
			//账号密码正确
			else if ( $arr['manager_psw'] == str::E($passWord) )
			{
				$code = 200;
				$tishi="登录成功!";
				
				//检查网站的基本配置
				CheckBasicConfig(true);
				
				//不是接口登陆才写入登录日志
				if( Get('name') == '' && Get('psw') == '' )
				{
					wmsql::Insert('@manager_login', $log);
				}
				
				//修改账号最后登录
				$data['manager_lastip'] = GetIp();
				$data['manager_lasttime'] = time();
				$where['manager_id'] = $arr['manager_id'];
				wmsql::Update('@manager_manager', $data, $where);
				
				//写入session信息
				Session( 'admin_id' , $arr['manager_id'] );
				Session( 'admin_cid' , $arr['manager_cid'] );
				Session( 'admin_name' , $arr['manager_name'] );
				Session( 'admin_account' , str::A( $userName , $passWord ) );
				
				//不是超级管理进行站群权限检测
				Session( 'admin_site' , '0' );//所有站点
				Session( 'admin_site_id' , '0' );//当前管理站点
				if( $arr['manager_cid'] != '0' )
				{
					$compWhere['table'] = '@system_competence';
					$compWhere['where']['comp_id'] =$arr['manager_cid'];
					$compData = wmsql::GetOne($compWhere);
					$siteArr = explode(',', $compData['comp_site']);
					//判断是否是站群模式
					$siteOpen = $C['config']['web']['site_open'];
					if( $siteOpen== '1' || ($siteOpen == '0' && $siteArr[0] == '0') )
					{
						//表单token删除
						FormDel();
						
						Session( 'admin_site' , $compData['comp_site']);
						Session( 'admin_site_id' , $siteArr[0]);
					}
					else
					{
						$code = 300;
						$tishi="对不起，站长关闭了站群模式，无法使用该帐号登录!";
						//写入错误日志
						$log['login_remark'] = '登录成功，但是关闭了站群模式并且账户没有主站点的管理权限。';
						wmsql::Insert('@manager_login', $log);
					}
				}
			}
		}
	}
}

if( Request('isAjax') == '0' )
{
	if( $code == '200' )
	{
		$backUrl = 'index.php?c=index';
	}
	else
	{
		$backUrl = 'index.php?c=login';
	}
	die("<script>alert('$tishi');location='$backUrl';</script>");
}
else
{
	if( Request('isAjax') == '1' )
	{
		Ajax($tishi,$code);
	}
}
?>