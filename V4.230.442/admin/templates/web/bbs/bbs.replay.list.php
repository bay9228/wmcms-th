<style>
.table tr {
    height: 35px;
}
thead th {
	text-align: center;
}
.list-tool{
	margin-bottom:5px;
}
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pl-10">
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                <a href="index.php?a=yes&c=operate.replay&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $type.$cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
				<a href="index.php?a=yes&c=operate.replay&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
				<?php if($bid == ''){ echo '<a href="index.php?a=yes&c=operate.replay&t=clear&module=bbs" data-toggle="doajax" data-confirm-msg="此操作不可撤回确定要清空吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空回帖</a>';}?>
				
				<a id="<?php echo $type.$cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>


<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="replay_id">ID</th>
				<th width="5%" data-order-field="user_status">是否审核</th>
				<th width="10%" data-order-field="replay_uid">用户</th>
				<th width="15%" >主题</th>
				<th>回帖内容</th>
				<th width="8%" data-order-field="replay_ip">Ip</th>
	            <th width="15%" data-order-field="replay_time">回帖时间</th>
	            <th width="10%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $type.$cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$url = tpl::url( 'bbs_bbs' , array('page'=>1,'bid'=>$v['bbs_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) );
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['replay_status'] , 0 , '<a href="javascript:;" onClick="'.$type.$cFun.'StatusAjax(1,'.$v['replay_id'].')"><span style="color:red">未审核</span></a>' , '<a href="javascript:;" onClick="'.$type.$cFun.'StatusAjax(0,'.$v['replay_id'].')"><span style="color:green">已审核</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['replay_id'].'"></td>
							<td style="text-align: center;">'.$v['replay_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['replay_nickname'].'</td>
							<td style="text-align: center;"><a href="'.$url.'" target="_blank">'.$v['bbs_title'].'</a></td>
							<td style="text-align: center;">'.str::DelHtml($v['replay_content']).'</td>
							<td style="text-align: center;">'.$v['replay_ip'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['replay_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="user-user-edit" data-title="查看评论详情" href="index.php?d=yes&c=operate.replay.detail&id='.$v['replay_id'].'">详情</a> 
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'delAjax('.$v['replay_id'].')">删除</a>
				            </td>
						</tr>';
					$i++;
				}
			}
			else
			{
				echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "没有数据了!")});</script>';
			}
			?>
			</tbody>
		</table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo $total;?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
    </div>
</div>

<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $type.$cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $type.$cFun;?>Form]").serializeArray();
	return op;
}
//删除
function <?php echo $type.$cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.replay&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的评论吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核评论
function <?php echo $type.$cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();

	//用户操作类型
	switch(status)
	{
		case 0:
			type = "取消审核";
	  		break;
	  		
		default:
			type = "通过审核";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.replay&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"回帖吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $type.$cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $type.$cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>