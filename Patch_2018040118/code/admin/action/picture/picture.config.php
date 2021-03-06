<?php
/**
* 图集配置文件保存处理器
*
* @version        $Id: picture.config.php 2016年5月15日 16:11  weimeng
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
		$data['config_value'] = $v;
		$data = str::Escape($data , 'e');
		
		wmsql::Update( $table , $data , $where);
	}

	//写入操作记录
	SetOpLog( '修改图集模块设置' , 'picture' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('picture');
	
	Ajax();
}
?>