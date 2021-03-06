<?php
/**
* 财务设置文件保存处理器
*
* @version        $Id: finance.config.php 2016年5月6日 14:45  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$table = '@config_config';

//如果请求信息存在
if( $post )
{
	foreach ($post as $k=>$v)
	{
		$where = array();
		$data = array();
		$where['config_id'] = $k;
		if( $k == 340 || $k == 341)
		{
			$v = strtotime($v);
		}
		$data['config_value'] = $v;
		$data = str::Escape($data , 'e');
		
		wmsql::Update( $table , $data , $where);
	}

	//写入操作记录
	SetOpLog( '修改财务模块设置' , 'finance' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('finance' , true);
	
	Ajax();
}
?>