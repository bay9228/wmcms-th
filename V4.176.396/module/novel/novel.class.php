<?php
/**
* 小说系统类文件
*
* @version        $Id: novel.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月29日 9:14 weimeng
*
*/
class novel
{
	static $typeTable = '@novel_type';
	static $novelTable = '@novel_novel';
	static $chapterTable = '@novel_chapter';
	/**
	 * 构造函数
	 * @param 参数1，选填，是否自动载入标签类
	 * @param string $labelLoad
	 */
	function __construct( $labelLoad = true )
	{
		if( $labelLoad )
		{
			//调用标签构造函数
			new novellabel();
		}
	}


	/**
	* 根据所得到的条件查询数据
	* @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	* @param 参数2，传递的sql条件
	* @param 参数3，选填，没有数据的提示字符串
	**/
	static function GetData( $type , $where='' , $errInfo='' )
	{
		$wheresql = self::GetWhere($where);
		//type为列表页数据获取
		switch ($type)
		{
			//列表页获取
			case 'type':
				$wheresql['table'][self::$typeTable] = 't';
				$wheresql['field'] = 't.*';
				$wheresql = GetFieldWhere('novel' , $wheresql);
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['table'][self::$novelTable] = 'a';
				$wheresql['left'][self::$typeTable.' as t'] = 't.type_id=a.type_id';
				$wheresql['left']['@novel_rec'] = 'rec_nid=novel_id';
				//不检查小说状态
				if( C('page.novel_check_status') !== 0 )
				{
					$wheresql['where']['novel_status'] = '1';
				}
				$wheresql['field'] = 'a.*,t.*';
				$wheresql = GetFieldWhere('novel' , $wheresql , 2);
				break;

			//推荐小说获取
			case 'rec':
				$wheresql['field'] = 'n.*,r.rec_rt,rec_img3,rec_img4,type_name,type_cname,type_pinyin,type_info';
				$wheresql['table'] = '@novel_rec as r';
				$wheresql['left'][self::$novelTable.' as n'] = 'rec_nid=novel_id';
				$wheresql['left'][self::$typeTable.' as t'] = 't.type_id=n.type_id';
				$wheresql = GetFieldWhere('novel' , $wheresql , 2);
				break;

			//最新章节
			case 'chapter':
				$wheresql['field'] = 'chapter_ispay,chapter_id,chapter_nid,chapter_name,chapter_istxt,chapter_cid,chapter_time,left(content,500) as content';
				$wheresql['table'] = self::$chapterTable;
				$wheresql['left']['@novel_content'] = 'chapter_cid=content_id';
				//不检查章节状态
				if( C('page.chapter_check_status') !== 0 )
				{
					$wheresql['where']['chapter_status'] = '1';
				}
				if ( @$wheresql['limit'] == '0,0')
				{
					$wheresql['limit'] = '';
				}
				break;

			//分卷查询
			case 'volume':
				$wheresql['table'] = '@novel_volume';
				break;

			//阅读章节
			case 'read':
				$wheresql['field']['n.*,t.*'] = '';
				$wheresql['field']['chapter_order,chapter_number,chapter_islogin,chapter_ispay,chapter_isvip,chapter_status,chapter_id,chapter_nid,chapter_name,chapter_istxt,chapter_cid,chapter_path,chapter_time,content,volume_name'] = '';
				$wheresql['table'] = self::$chapterTable;
				$wheresql['left']['@novel_content'] = 'chapter_cid=content_id';
				$wheresql['left']['@novel_volume'] = 'chapter_vid=volume_id';
				$wheresql['left']['@novel_novel as n'] = 'chapter_nid=novel_id';
				$wheresql['left']['@novel_type as t'] = 't.type_id=n.type_id';
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}
		
		//分页处理
		if( @$wheresql['list'] )
		{
			page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
		}
		
		$data = wmsql::GetAll($wheresql);
		//如果数组为空并且错误提示不为空则输出错误提示。
		if( $type == 'type' && ( @$where['t.type_id'] == '0' ||  @$where['t.type_pinyin'] == 'all') )
		{
			$data[0] = array(
				'type_name'=>'全部分类',
				'type_cname'=>'全部',
				'type_id'=>'0',
				'type_pinyin'=>'all',
			);
		}
		else if( !$data && $errInfo != '' )
		{
			tpl::ErrInfo($errInfo);
		}
		return $data;
	}


	/**
	* 获得字符串中的条件sql
	* 返回值字符串
	* @param 参数1：需要查询的字符串。
	**/
	static function GetWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'tid' =>'t.type_id',
			'type_id' =>'t.type_id',
			'分类' =>'t.type_id',
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
			'父级分类' =>'type_topid',

			'推荐顺序' =>'rec_order',
			'推荐倒序' =>'rec_order desc',
			'首页封面' =>'rec_icr',
			'首页精品' =>'rec_ibr',
			'首页推荐' =>'rec_ir',
			'分类封面' =>'rec_ccr',
			'分类精品' =>'rec_cbr',
			'分类推荐' =>'rec_cr',

			'是否写作' =>'novel_newcid',
			'已写作' =>'[>->0]',
			'未写作' =>'0',
			'最新入库' =>'novel_createtime desc',
			'最新更新' =>'novel_uptime desc',
			'更新时间' =>'chapter_time desc',
			'顶' =>'novel_ding desc',
			'踩' =>'novel_cai desc',
			'评论' =>'novel_replay desc',
			'评分' =>'novel_score desc',
			'字数' =>'novel_wordnumber desc',
			'日点击' =>'novel_todayclick desc',
			'周点击' =>'novel_weekclick desc',
			'月点击' =>'novel_monthclick desc',
			'年点击' =>'novel_yearclick desc',
			'总点击' =>'novel_allclick desc',
			'日收藏' =>'novel_todaycoll desc',
			'周收藏' =>'novel_weekcoll desc',
			'月收藏' =>'novel_monthcoll desc',
			'年收藏' =>'novel_yearcoll desc',
			'总收藏' =>'novel_allcoll desc',
			'日推荐' =>'novel_todayrec desc',
			'周推荐' =>'novel_weekrec desc',
			'月推荐' =>'novel_monthrec desc',
			'年推荐' =>'novel_yearrec desc',
			'总推荐' =>'novel_allrec desc',

			'是' =>'1',
			'否' =>'0',

			'小说进程' =>'novel_process',
			'连载中' =>'1',
			'已完结' =>'2',
			'已断更' =>'3',

			'分卷id' =>'chapter_vid',
			'章节顺序' =>'chapter_order',
			'章节倒序' =>'chapter_order desc',
		);

		return tpl::GetWhere($where,$arr);
	}
	
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 * @param 参数3，选填，是否有where条件了
	 */
	static function GetPar( $type , $par , $where = array()){
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;
		
			case 'content':
				$parName['id'] = 'novel_id';
				$parName['pinyin'] = 'novel_pinyin';
				break;
		}
		$where = CheckPar(  $parName , $par , $where );
		return $where;
	}
	
	
	/**
	 * 获得真实的小说内容连接
	 * @param 参数1，必须，内容数组。
	 * @param 参数1，选填，默认为2最新章节，1为第一章。
	 */
	static function GetReadUrl( $v , $type = '2' )
	{
		//章节字段
		if( $type == '1' )
		{
			$field = 'novel_startcid';
		}
		else
		{
			$field = 'novel_newcid';
		}
		
		
		if ( $v[$field] == '0' )
		{
			$url = C('system.content.no_url',null,'lang');
		}
		else
		{
			$url = tpl::url( 'novel_read' , array('cid'=>$v[$field],'tid'=>$v['type_id'],'nid'=>$v['novel_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin']));
		}
		
		return $url;
	}
	
	
	/**
	 * 获得小说属性
	 * @param 参数1，必须，标签名字
	 * @param 参数2，必须，当恰属性的值
	 * @param 参数3，必须，模版的字符串
	 */
	static function GetAttr( $label = '是否推荐' , $val , $str = '' )
	{
		if( $val == '1' )
		{
			$arr = array($label=>'','/'.$label=>'');
			$str = tpl::Rep( $arr , $str);
		}
		else
		{
			$arr = array('{'.$label.'}[a]{/'.$label.'}'=>'');
			$str = tpl::Rep( $arr , $str , 3);
		}
		
		return $str;
	}
	
	
	/**
	 * 获得小说进程
	 * @param 参数1，必须，状态码
	 */
	static function GetProcess( $sta )
	{
		switch ( $sta )
		{
			case "1":
				return C( 'novel.par.novel_process_1' , null , 'lang');
				break;
				
			case "2":
				return C( 'novel.par.novel_process_2' , null , 'lang');
				break;
				
			case "3":
				return C( 'novel.par.novel_process_3' , null , 'lang');
				break;
		}
	}


	/**
	 * 获得小说状态
	 * @param 参数1，必须，状态码
	 */
	static function GetStatus( $sta )
	{
		switch ( $sta )
		{
			case "0":
				return C( 'novel.par.novel_status_0' , null , 'lang');
				break;
	
			case "1":
				return C( 'novel.par.novel_status_1' , null , 'lang');
				break;
	
			case "2":
				return C( 'novel.par.novel_status_2' , null , 'lang');
				break;
		}
	}

	/**
	 * 获得小说类型
	 * @param 参数1，必须，类型代码
	 */
	static function GetType( $val )
	{
		switch ( $val )
		{
			case "1":
				return C( 'novel.par.novel_type_1' , null , 'lang');
				break;
	
			case "2":
				return C( 'novel.par.novel_type_2' , null , 'lang');
				break;
		}
	}


	/**
	 * 获得小说签约文字
	 * @param 参数1，必须，类型代码
	 * @param 参数2，选填，是否返回签约的等级状态
	 */
	static function GetCopyRigth( $val , $boolean = false)
	{
		switch ( $val )
		{
			case "0":
				return C( 'novel.par.novel_copyright_0' , null , 'lang');
				break;
				
			case "1":
				return C( 'novel.par.novel_copyright_1' , null , 'lang');
				break;
	
			case "2":
				return C( 'novel.par.novel_copyright_2' , null , 'lang');
				break;
		}
	}

	/**
	 * 获得小说上架文字
	 * @param 参数1，必须，类型代码
	 */
	static function GetSell( $val )
	{
		switch ( $val )
		{
			case "0":
				return C( 'novel.par.novel_sell_0' , null , 'lang');
				break;
	
			case "1":
				return C( 'novel.par.novel_sell_1' , null , 'lang');
				break;
		}
	}
	
	
	/**
	 * 获得小说最新章节名字
	 * @param 参数1，必须，章节名字
	 */
	static function GetChaper( $chapter )
	{
		if( $chapter == '' || $chapter == '0' )
		{
			$chapter = C( 'novel.par.novel_chapter' , null , 'lang');
		}
		return $chapter;
	}
	


	/**
	 * 获得小说推荐的标题
	 * @param 参数1，必须，小说内容
	 */
	static function GetRT( $v )
	{
		if( @$v['rec_rt'] == '' )
		{
			$v['rec_rt'] = $v['novel_name'];
		}
		return @$v['rec_rt'];
	}


	/**
	 * 获得小说推荐的封面
	 * @param 参数1，必须，小说内容
	 * @param 参数2，选填，是否指定显示的推荐封面版本
	 */
	static function GetRC( $v , $ptInt = '0')
	{
		$recImg = '';
		if( $ptInt == '0' )
		{
			$ptInt = ua::$ptInt;
		}
		//触屏推荐封面
		if( $ptInt == '3' && @$v['rec_img3'] != '')
		{
			$recImg = @$v['rec_img3'];
		}
		//电脑推荐封面
		else if( @$v['rec_img4'] != '' )
		{
			$recImg = @$v['rec_img4'];
		}
		if( $recImg == '' )
		{
			$recImg =  $v['novel_cover'];
		}
		return $recImg;
	}

	
	/**
	 * 获得章节数量
	 * @param 参数1，必须，书籍id
	 */
	static function GetChapterCount($nid)
	{
		$where['table'] = self::$chapterTable;
		$where['where']['chapter_nid'] = "$nid";
		$where['where']['chapter_status'] = 1;
		return wmsql::GetCount($where);
	}
	

	/**
	 * 获得分类筛选的url
	 * @param 参数1，必须，书籍id
	 */
	static function GetRetrieval($tid)
	{
		$pageArr = UrlFormat();
		foreach (UrlFormat(tpl::url('novel_type_retrieval',null,'1')) as $kk=>$vv)
		{
			if( $kk == 'tid' )
			{
				$pageArr[$kk] = $tid;
			}
			else
			{
				$pageArr[$kk] = intval(Get($kk,'0'));
			}
		}
		return tpl::Url('novel_type_retrieval' , $pageArr);
	}
}
?>