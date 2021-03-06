<?php
/**
* 道具赠送处理器
*
* @version        $Id: props.give.php 2017年3月18日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//判断用户是否登录了
$uid = user::GetUid();
str::EQ( $uid , 0 , $lang['user']['no_login']['info'], $ajax );
//是否开启了赠送
str::EQ( $novelConfig['give_open'], 0 , $lang['novel']['action']['give_close'] , $ajax );
//接受参数
$nid = str::Int( Request('nid') );
$pid = str::Int( Request('pid') );
$number = abs(str::Int( Request('number' , 1) ));


//查询内容是否存在
$novelData = $tableSer->GetData('novel',$nid);
if( !$novelData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}

//出售道具
$propsMod = NewModel('props.props');
//根据作者id查询出用户id，
//$authorMod = NewModel('author.author');
//$authorUid = str::GetKey($authorMod->GetAuthor($novelData['author_id'] , 2) , 'user_id');
//$propsMod->tuid = $authorUid;
$propsData = $propsMod->Sell('novel',$pid,$nid);
switch ($propsData)
{
	//道具不存在
	case '201':
		ReturnData( $lang['novel']['action']['props_no'] , $ajax );
		break;
	//没有上架
	case '202':
		ReturnData( $lang['novel']['action']['props_no'] , $ajax );
		break;
	//库存不足
	case '203':
		ReturnData( $lang['novel']['action']['props_stock'] , $ajax );
		break;
	//用户金币1数量不足
	case '204':
		ReturnData( $lang['user']['gold1_no'] , $ajax );
		break;
	//用户金币2数量不足
	case '205':
		ReturnData( $lang['user']['gold2_no'] , $ajax );
		break;
	default:
		$option = unserialize($propsData['props_option']);

		//小说的资金属性变更
		$data['gold1'] = $propsData['props_gold1']*$number;
		$data['gold2'] = $propsData['props_gold2']*$number;
		$data['uid'] = $uid;
		$data['nid'] = $nid;
		$data['aid'] = $novelData['author_id'];
		$data['copy'] = $novelData['novel_copyright'];
		$data['sign'] = $novelData['novel_sign_id'];
		$data['rec'] = $option['rec'];
		$data['month'] = $option['month'];
		$data['author_exp'] = $option['author_exp'];
		$data['fans_exp'] = $option['fans_exp'];
		$data['user_exp'] = $option['user_exp'];
		$data['ticket_remark'] = 'props';
		$data['log_type'] = 'props_income';
		$data['log_remark'] = '道具收入';
		$consumeMod = NewModel('novel.consume');
		$consumeMod->Update($data);
		
		//返回信息
		ReturnData( $lang['novel']['operate']['props']['success'] , $ajax , 200);
		break;
}
?>