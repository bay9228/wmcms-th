<?php
/**
* 小说章节模型
*
* @version        $Id: chapter.model.php 2017年1月8日 13:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ChapterModel
{
	//分类表
	public $chapterTable = '@novel_chapter';
	//内容表
	public $contentTable = '@novel_content';
	//小说表
	public $novelTable = '@novel_novel';
	//分卷表
	public $volumeTable = '@novel_volume';
	//小说配置
	private $novelConfig;
	//保存路径
	private $novelSave;
	private $chapterSave;
	//加密字符串
	private $enStr;
	//网站api信息
	private $appid;
	private $apikey;
	private $sckey;
	
	//构造函数
	public function __construct()
	{
		global $C;
		$this->appid = $C['config']['api']['system']['api_appid'];
		$this->apikey = $C['config']['api']['system']['api_apikey'];
		$this->sckey = $C['config']['api']['system']['api_secretkey'];
		//获取配置文件
		$this->novelConfig = GetModuleConfig('novel');
		//保存路径
		$this->novelSave = WMROOT.$this->novelConfig['novel_save'];
		$this->chapterSave = WMROOT.$this->novelConfig['chapter_save'];
		//自定义加密字符串
		$this->enStr = $this->novelConfig['novel_en_str'];
		if( $this->enStr == '' )
		{
			$this->enStr = $this->appid.$this->apikey.$this->sckey;
		}
	}
	
	
	/**
	 * 插入章节
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		return wmsql::Insert($this->chapterTable, $data);
	}
	
	/**
	 * 修改小说内容
	 * @param 参数1，必须，修改的内容
	 */
	function Update($data , $whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['chapter_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}

		return wmsql::Update($this->chapterTable, $data, $where);
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['chapter_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->chapterTable , $where);
	}
	
	
	/**
	 * 检查小说在章节名字是否存在
	 * @param 参数1，必须，小说章节的名字
	 * @param 参数2，必填，小说的id
	 * @param 参数3，选填，小说章节deid
	 */
	function CheckName( $name , $nid , $cid = '0' )
	{
		$where['chapter_name'] = $name;
		$where['chapter_nid'] = $nid;
		$where['chapter_id'] = array('<>',$cid);
		return $this->GetCount($where);
	}

	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$wheresql['table'] = $this->chapterTable;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql);
	}

	
	/**
	 * 获得小说的最新章节数据
	 * @param 参数1，必须，小说的id
	 */
	function GetNewChapter($nid)
	{
		$where['table'] = $this->chapterTable;
		$where['where']['chapter_nid'] = $nid;
		$where['order'] = 'chapter_order desc';
		$where['limit'] = '1';
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得小说的最新顺序
	 * @param 参数1，必须，小说的id
	 */
	function GetChapterOrder($nid)
	{
		$data = $this->GetNewChapter($nid);
		if ( $data )
		{
			$order = $data['chapter_order'] + 1;
		}
		else
		{
			$order = 1;
		}
		return $order;
	}

	/**
	 * 获取小说配置
	 * @param 参数1，选填，参数名字，不填则返回全部
	 */
	function GetConfig($key = '')
	{
		if( $key != '' )
		{
			return $this->novelConfig[$key];
		}
		else
		{
			return $this->novelConfig;
		}
	}

	
	/**
	 * 创建小说txt
	 * @param 参数1，必填，分类id
	 * @param 参数2，必填，内容id
	 * @param 参数3，必填，小说名字
	 * @param 参数4，必填，简介内容
	 */
	function CreateNovel( $tid , $nid , $name , $info )
	{
		if( $this->GetConfig('data_type') == '1' )
		{
			$fileName = $this->GetNovelFileName($tid,$nid);
			if( !file_exists($fileName) )
			{
				$fileContent = str::ToTxt($name)."\r\n".$this->GetConfig('novel_head')."\r\n".str::ToTxt($info)."\r\n";
				file::CreateFile($fileName, $fileContent);
			}
		}
		return true;
	}
	
	
	/**
	 * 更新小说txt
	 * @param 参数1，必填，分类id
	 * @param 参数2，必填，内容id
	 * @param 参数3，必填，小说名字
	 * @param 参数4，必填，简介内容
	 */
	function UpdateNovel( $file , $data )
	{
		//章节列表和章节内容
		$content = '';
		$chapterList = array();
		//不存在文件就创建文件。
		if( !file_exists($file) )
		{
			$this->CreateNovel($data['type_id'],$data['novel_id'],$data['novel_name'],$data['novel_info'] );
			$chapterList = $this->GetByNid($data['novel_id']);
		}
		//存在，但是不是最新的
		else if( file_exists($file) && $data['novel_uptime'] > filemtime($file) )
		{
			$where['novel_uptime'] = array('>',filemtime($file));
			$chapterList = $this->GetByNid($data['novel_id'],$where);
		}
		//如果数据不为空就写入
		if( !empty($chapterList) )
		{
			//循环查询数据
			foreach ($chapterList as $k=>$v)
			{
				$content = $content.$this->GetConfig('chapter_start')."\r\n".
						$v['chapter_name']."\r\n".
						str::ToTxt($this->GetTxtContent($v['type_id'],$v['novel_id'],$v['chapter_id'],$v['chapter_istxt']))."\r\n".
						$this->GetConfig('chapter_end')."\r\n";
			}
			//将章节内容写入到完整的txt文件
			file::CreateFile($file, $content , '1');
		}
		return true;
	}
	
	
	/**
	 * 写入章节信息
	 * @param 参数1，必填，操作类型，是增加add还是修改edit
	 * @param 参数2，必填，小说id
	 * @param 参数3，必填，章节id
	 * @param 参数4，必填，章节内容
	 * @param 参数5，选填，创建章节是否需要审核，默认是需要审核
	 */
	function CreateChapter( $type , $nid , $cid , $content , $status = 0)
	{
		$where['table'] = $this->chapterTable;
		$where['left']['@novel_novel'] = 'chapter_nid=novel_id';
		$where['where']['chapter_id'] = $cid;
		$arr = WMSql::GetOne($where);
	
		if( $arr && $arr['novel_name'] != '' )
		{
			//数据入库模式为生成txt
			if( $this->GetConfig('data_type') == '1' )
			{
				//创建新的章节路径
				$fileName = $this->GetChapterFileName($arr['type_id'],$arr['novel_id'],$arr['chapter_id']);
				
				//小说章节标题和内容
				$title = str::ToTxt($arr['chapter_name']);
				$content = str::ToTxt($content);
				//将章节内容写入到txt文件
				file::CreateFile($fileName, $content , '1');
			}
			//数据模式为入库
			else
			{
				//章节内容
				$contentData['content'] = $content;
				//新增数据
				if( $type == 'add' || $arr['chapter_cid'] == '0' )
				{
					//插入数据
					$chapterData['chapter_cid'] = wmsql::Insert($this->contentTable, $contentData);
					//将最新的内容id写入章节里面
					wmsql::Update($this->chapterTable, $chapterData, $where['where']);
				}
				//修改数据
				else
				{
					//修改章节内容
					$contentWhere['content_id'] = $arr['chapter_cid'];
					wmsql::Update($this->contentTable, $contentData, $contentWhere);
				}
			}
		}
		return true;
	}
	
	
	/**
	 * 获得章节的内容
	 * @param 参数1，必须，字符串，章节id
	 */
	function GetById( $cid )
	{
		$where['table'] = $this->chapterTable;
		$where['field'] = 'type_id,author_id,chapter_status,chapter_isvip,chapter_ispay,chapter_istxt,
				chapter_time,chapter_nid,chapter_id,chapter_vid,chapter_cid,chapter_name,chapter_number,novel_id,author_id,
				volume_name,volume_desc';
		$where['left'][$this->novelTable] = 'novel_id=chapter_nid';
		$where['left'][$this->volumeTable] = 'volume_id=chapter_vid';
		$where['where']['chapter_id'] = $cid;
		$data = wmsql::GetOne($where);
		$data['is_content'] = true;
		
		if( $data['chapter_istxt'] == '1' )
		{
			$data['content'] = $this->GetTxtContent($data['type_id'],$data['chapter_nid'],$data['chapter_id']);
			if( $data['content'] == false )
			{
				$data['is_content'] = false;
			}
		}
		else
		{
			$contentWhere['table'] = $this->contentTable;
			$contentWhere['where']['content_id'] = $data['chapter_cid'];
			$contentData = wmsql::GetOne($contentWhere);
			$data['content'] = $contentData['content'];
		}
		
		return $data;
	}

	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->chapterTable;
		$wheresql['left'][$this->novelTable] = 'chapter_nid=novel_id';
		if( is_array($where) )
		{
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['chapter_id'] = $where;
		}
		return wmsql::GetOne($wheresql);
	}
	

	/**
	 * 获得小说的全部章节
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，其他的参数条件
	 * @param 参数3，选填，查询的字段
	 */
	function GetByNid($nid , $where = array() , $field ='')
	{
		//字段
		if( $field == '' )
		{
			$field = 'chapter_istxt,type_id,novel_id,chapter_id,chapter_cid,chapter_name';
		}
		$wheresql['table'] = $this->chapterTable;
		$wheresql['field'] = $field;
		$wheresql['left'][$this->novelTable] = 'chapter_nid=novel_id';
		$wheresql['where']['chapter_nid'] = $nid;
		$wheresql['order'] = 'chapter_order';
		if( !empty($where) )
		{
			$wheresql['where'] = $where;
		}
		return wmsql::GetAll($wheresql);
	}
	
	
	/**
	 * 获得小说章节txt内容
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，小说章节id
	 * @param 参数4，选填，是否是生成txt
	 */
	function GetTxtContent($tid = '', $nid = '' , $cid = '' , $isTxt = '1')
	{
		if( $isTxt == '1' )
		{
			$file = $this->GetChapterFileName($tid,$nid,$cid);
			$content = str::ToHtml(file::GetFile($file));
			if( !file_exists($file) )
			{
				return false;
			}
			else
			{
				return $content;
			}
		}
		else
		{
			$where['table'] = $this->contentTable;
			$where['where']['content_id'] = $cid;
			$data = wmsql::GetOne($where);
			return $data['content'];
		}
		
	}
	
	
	/**
	 * 获得章节文件名字
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 * @param 参数3，必须，小说章节id
	 */
	function GetChapterFileName($tid = '', $nid = '' , $cid = '')
	{
		$nid = str::E($nid.$this->enStr);
		$cid = str::E($cid.$this->enStr);
		return tpl::Rep(array('tid'=>$tid,'nid'=>$nid,'cid'=>$cid) , $this->chapterSave);
	}
	/**
	 * 获得小说文件名字
	 * @param 参数1，必须，小说分类id
	 * @param 参数2，必须，小说id
	 */
	function GetNovelFileName($tid = '', $nid = '')
	{
		$nid = str::E($nid.$this->enStr);
		return tpl::Rep(array('tid'=>$tid,'nid'=>$nid) , $this->novelSave);
	}
	
	
	/**
	 * 检查小说章节的订阅状态
	 * @param 参数1，必须,章节的内容数组
	 */
	function CheckChapterSub($data)
	{
		$authorData = array();
		if( !class_exists('user') )
		{
			$uid = 0;
		}
		else
		{
			$uid = user::GetUid();
		}

		$authorData['user_id'] = 0;
		//获得作者信息
		if( $data['author_id'] > 0 )
		{
			$authorMod = NewModel('author.author');
			$authorData = $authorMod->GetAuthor($data['author_id'] , 2);
		}
		
		//判断章节审核状态
		if( $data['chapter_status'] !='1' && $authorData['user_id'] != $uid )
		{
			return 201;
		}
		//判断是否需要登录
		else if( $data['chapter_islogin']=='1'  && $uid == 0 )
		{
			return 202;
		}
		//判断是否需要付费
		else if( $data['chapter_ispay']=='1' )
		{
			//没有登录
			if( $uid == 0 )
			{
				return 202;
			}
			//作者不是自己
			else if ($authorData['user_id'] != $uid )
			{
				//是否买了全本、包月、单章
				$subMod = NewModel('novel.sublog');
				$isSub = $subMod->IsSub($uid , $data['novel_id'] , $data['chapter_id'] , $data);
				//如果没有订阅
				if( !$isSub )
				{
					$data['is_sub'] = 0;
				}
			}
		}
		
		if($data['content'] == '' )
		{
			$data['content'] = $this->GetTxtContent($data['type_id'], $data['novel_id'] , $data['chapter_id'],$data['chapter_istxt']);
		}
		return $data;
	}
	
	
	/**
	 * 获得小说章节列表
	 * @param 参数1，必须，小说id
	 * @param 参数2，必须，分卷id
	 */
	function GetList( $wheresql )
	{
		$where['table'] = $this->chapterTable;
		$where['filed'] = 'chapter_id,chapter_status,chapter_isvip,chapter_ispay,chapter_number,chapter_nid,chapter_vid,chapter_order,chapter_time';
		
		$where = MergeWhere($where , $wheresql);
		return wmsql::GetAll($where);
	}
}
?>