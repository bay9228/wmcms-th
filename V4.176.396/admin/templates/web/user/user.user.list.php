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
		<div class="list-tool pl-15">
            <span >ดำเนินการด่วน : </span>
			<a href="index.php?a=yes&c=user.user&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="คุณต้องการตรวจสอบรายการที่เลือกหรือไม่?" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> ตรวจสอบหลายรายการ</a>
			<a href="index.php?a=yes&c=user.user&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="คุณต้องการลบรายการที่เลือกหรือไม่?" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> ลบหลายรายการ</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">ค้นหาด่วน : </span>

                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">ค้นหา</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> รีเฟรช</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="user_id">ไอดี</th>
				<th width="7%">ช่องทาง</th>
				<th data-order-field="user_name">ชื่อผู้ใช้</th>
				<th width="5%" data-order-field="user_status">สถานะ</th>
				<th width="8%" data-order-field="user_email">อีเมล์</th>
				<th width="5%" data-order-field="user_sex">เพศ</th>
				<th width="8%" data-order-field="user_exp">ระดับ</th>
				<th width="8%" data-order-field="user_gold1"><?php echo $configArr['gold1_name'];?></th>
				<th width="8%" data-order-field="user_gold2"><?php echo $configArr['gold2_name'];?></th>
	            <th width="12%" data-order-field="user_regtime">เวลาลงทะเบียน</th>
	            <th width="15%">ดำเนินการ</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['user_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['user_id'].')"><span style="color:red">รอตรวจ</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['user_id'].')"><span style="color:green">ตรวจแล้ว</span></a>');
					$sex = str::CheckElse( $v['user_sex'] , 1 , 'ชาย' , 'หญิง');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['user_id'].'"></td>
							<td style="text-align: center;">'.$v['user_id'].'</td>
							<td style="text-align: center;">'.$userMod->GetUserType($v['user_type']).'</td>
							<td style="text-align: center;">'.$v['user_name'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td>'.$v['user_email'].'</span></td>
							<td style="text-align: center;">'.$sex.'</td>
							<td style="text-align: center;">'.$v['level_name'].'</td>
							<td style="text-align: center;">'.$v['user_gold1'].'</td>
							<td style="text-align: center;">'.$v['user_gold2'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i' , $v['user_regtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<button type="button" class="btn btn-warning radius" id="'.$cFun.'dropdownMenu_'.$v['user_id'].'" data-toggle="dropdown">เพิ่มเติม<span class="caret"></span></button>
								<ul class="dropdown-menu" aria-labelledby="'.$cFun.'dropdownMenu_'.$v['user_id'].'" style="width:80px">
							      <li><a href="index.php?d=yes&c=user.user.reward&id='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="ดำเนินการรางวัลและบทลงโทษ" data-width="550" data-height="180">รางวัล/ลงโทษ</a></li>
							      <li><a href="index.php?d=yes&c=user.msg.send&id='.$v['user_id'].'" data-mask="true" data-toggle="dialog" data-title="ส่งข้อความ" data-width="700" data-height="550">ส่งข้อความ</a></li>
								</ul>
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="user-user-edit" data-title="แก้ไขข้อมูล" href="index.php?d=yes&c=user.user.edit&t=edit&id='.$v['user_id'].'">แก้ไข</a>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['user_id'].')">ลบ</a>
				            </td>
						</tr>';
					$i++;
				}
			}
			else
			{
				echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "ไมมีข้อมูล!")});</script>';
			}
			?>
			</tbody>
		</table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>ต่อหน้า&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
            </select>
        </div>
        <span>&nbsp;รายการจากทั้งหมด <?php echo $total;?> รายการ</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
    </div>
</div>

<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除用户
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=user.user&t=del";
	ajaxOptions['confirmMsg'] = "คุณต้องการลบผู้ใช้ที่เลือกหรือไม่?";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核用户
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//用户操作类型
	switch(status)
	{
		case 0:
			type = "ละทิ้ง";
	  		break;

		default:
			type = "ตรวจสอบ";
	  		break;
	}

	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=user.user&t=status";
	ajaxOptions['confirmMsg'] = "ต้องการ"+type+"ผู้ใช้หรือไม่?";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
