<?php
/**
* 文章分类控制器文件
*
* @version        $Id: article.type.edit.php 2016年4月11日 14:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$tempSer = AdminNewClass('system.templates');
$seoSer = AdminNewClass('system.seo');
$conSer = AdminNewClass('system.config');
$typeSer = AdminNewClass('article.type');

//查询所有分类
$typeArr = $typeSer->GetType();
//查询所有分类
$statusArr = $typeSer->GetStatus();

//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@article_type';
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
			$top['name'] = 'หมวดหมู่ยอดนิยม';
		}

		//查询使用的模版信息
		$temp['name'] = $tempSer->GetTemp( $data['type_tempid'] , 'temp_name' );
		$temp['cname'] = $tempSer->GetTemp( $data['type_ctempid'] , 'temp_name' );
		$temp['tiname'] = $tempSer->GetTemp( $data['type_titempid'] , 'temp_name' );

		//查询使用的静态路径
		$html['tindex'] = $seoSer->GetHtml( $curModule , 'tindex' , $id );
		$html['list'] = $seoSer->GetHtml( $curModule , 'list' , $id );
		$html['content'] = $seoSer->GetHtml( $curModule , 'content' , $id );
	}
}
else
{
	$data['type_status'] = '1';
	$data['type_tempid'] = $data['type_ctempid'] = $data['type_titempid'] = 0;
	//查询使用的静态路径
	$html['tindex'] = '/html/article/{tid}.html';
	$html['list'] = '/html/article/list/{tid}_{page}.html';
	$html['content'] = '/html/article/content/{aid}.html';
}
?>
