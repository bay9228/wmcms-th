<?php
/**
 * 小说首页
 *
 * @version        $Id: index.php 2015年8月9日 21:43  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime		   2016年1月7日 9:38 weimeng
 *
 */
//引入模块公共文件
require_once 'novel.common.php';

//获得页面的标题等信息
C('page' ,  array('pagetype'=>'novel_index' ,'dtemp'=>'novel/index.html'));
//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>