<?php
/**
* 专题分类控制器文件
*
* @version        $Id: operate.zt.typeedit.php 2018年8月13日 21:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
$seoSer = AdminNewClass('system.seo');
$typeSer = AdminNewClass('operate.zt');
//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'type_add';}


//如果id大于0
if ( $type == 'type_edit')
{
	$where['table'] = '@zt_type';
	$where['where']['type_id'] = $id;

	$data = wmsql::GetOne($where);
	if( $data )
	{
		//上级分类的名字
		if( $data['type_topid'] > 0 )
		{
			$where['where']['type_id'] = $data['type_topid'];
			$topData = wmsql::GetOne($where);
			$top['name'] = $topData['type_name'];
		}
		else
		{
			$top['name'] = '顶级分类';
		}
		
		//查询使用的模版信息
		$temp['name'] = $tempSer->GetTemp( $data['type_tempid'] , 'temp_name' );
		$temp['cname'] = $tempSer->GetTemp( $data['type_ctempid'] , 'temp_name' );

		//查询使用的静态路径
		$html['list'] = $seoSer->GetHtml( $curModule , 'list' , $id );
		$html['content'] = $seoSer->GetHtml( $curModule , 'content' , $id );
	}
}
else
{
	$data['type_tempid'] = $data['type_ctempid'] = 0;
	
	$html['list'] = '/html/zt/{tid}_{page}.html';
	$html['content'] = '/html/zt/{id}.html';
}
?>