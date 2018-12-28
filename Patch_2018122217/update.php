<?php
$configTable = wmsql::table('config_config');
$groupTable = wmsql::table('config_group');
$optionTable = wmsql::table('config_option');
$menuTable = wmsql::table('system_menu');
$urlsTable = wmsql::table('seo_urls');
$keysTable = wmsql::table('seo_keys');
wmsql::exec("INSERT INTO `{$menuTable}` (`menu_title`, `menu_name`, `menu_pid`, `menu_order`, `menu_file`, `menu_ico`) VALUES ('URL模式', 'urlmode', '12', '1', 'system.seo.urlmode', 'fa-link'); ");

wmsql::exec("UPDATE `{$configTable}` SET `config_module` = 'urlmode' WHERE `config_id` = '13'; 
UPDATE `{$configTable}` SET `config_module` = 'urlmode' WHERE `config_id` = '301'; 
UPDATE `{$optionTable}` SET `option_title` = '不使用HTML' WHERE `option_id` = '246'; 
UPDATE `{$optionTable}` SET `option_title` = '使用HTML' WHERE `option_id` = '247'; 
UPDATE `{$configTable}` SET `config_formtype` = 'select' WHERE `config_id` = '13';");

wmsql::exec("UPDATE `{$optionTable}` SET `option_title` = '传统动态模式' WHERE `option_id` = '4'; 
UPDATE `{$optionTable}` SET `option_title` = '传统伪静态模式' WHERE `option_id` = '3'; ");

wmsql::exec("UPDATE `{$optionTable}` SET `option_order` = '1' WHERE `option_id` = '4'; 
UPDATE `{$optionTable}` SET `option_order` = '2' WHERE `option_id` = '3'; ");

wmsql::exec("INSERT INTO `{$optionTable}` (`config_id`, `option_title`, `option_value`, `option_order`) VALUES ('13', '普通模式', '3', '3'); 
INSERT INTO `{$optionTable}` (`config_id`, `option_title`, `option_value`, `option_order`) VALUES ('13', '兼容模式', '4', '4'); 
INSERT INTO `{$optionTable}` (`config_id`, `option_title`, `option_value`, `option_order`) VALUES ('13', 'PATHINFO模式', '5', '5'); 
INSERT INTO `{$optionTable}` (`config_id`, `option_title`, `option_value`, `option_order`) VALUES ('13', 'REWRITE模式', '6', '6'); ");

wmsql::exec("INSERT INTO `{$menuTable}` (`menu_status`, `menu_title`, `menu_name`, `menu_pid`, `menu_group`, `menu_order`, `menu_file`) VALUES ('0', 'URL模式保存', 'config', '757', '2', '1', 'system.seo.urlmode'); 
INSERT INTO `{$groupTable}` (`group_name`, `group_remark`) VALUES ('route', '路由配置组'); ");

wmsql::exec("UPDATE `{$configTable}` SET `group_id` = '15' WHERE `config_id` = '13'; 
UPDATE `{$configTable}` SET `group_id` = '15' WHERE `config_id` = '301'; ");

wmsql::exec("INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_value`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', 'URL参数分割字符', 'urlmode_exp', '/', 'input', 'URL参数的分割字符', '3'); 
INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_value`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', 'URL文件后缀', 'urlmode_suffix', '.html', 'input', 'URL文件后缀，可以为空', '4'); 
INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_value`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', '普通模式模块参数名', 'urlmode_par_odnr1', 'module', 'input', '普通模式的模块参数名', '5'); 
INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_value`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', '普通模式文件参数名', 'urlmode_par_odnr2', 'file', 'input', '普通模式的文件参数名', '6'); 
INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_value`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', '兼容模式参数名', 'urlmode_par_cptb', 'path', 'input', '兼容模式的参数名', '7'); 
INSERT INTO `{$configTable}` (`group_id`, `config_module`, `config_title`, `config_name`, `config_formtype`, `config_remark`, `config_order`) VALUES ('15', 'urlmode', 'URL路由', 'urlmode_route', 'textarea', 'url参数的路由匹配', '8'); ");

wmsql::exec("ALTER TABLE `{$urlsTable}` ADD COLUMN `urls_url3` VARCHAR(250) NULL COMMENT '普通模式地址' AFTER `urls_url2`, ADD COLUMN `urls_url4` VARCHAR(250) NULL COMMENT '兼容模式地址' AFTER `urls_url3`, ADD COLUMN `urls_url5` VARCHAR(250) NULL COMMENT 'PATHINFO模式地址' AFTER `urls_url4`, ADD COLUMN `urls_url6` VARCHAR(250) NULL COMMENT 'REWRITE模式地址' AFTER `urls_url5`; ");

wmsql::exec("UPDATE `{$urlsTable}` SET `urls_url3` = '/' ,`urls_url4` = '/' ,`urls_url5` = '/' ,`urls_url6` = '/' WHERE `urls_id` = '1'; 
UPDATE `{$urlsTable}` SET `urls_url3` = '/?pt={pt}&module=novel&file=type&tid={tid}&page={page}' ,`urls_url4` = '/?path=/novel/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/novel/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/novel/type/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '2'; 
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=info&pt={pt}&tid={tid}&nid={nid}' ,`urls_url4` = '/?path=/novel/info/pt/{pt}/tid/{tid}/nid/{nid}' ,`urls_url5` = '/index.php/novel/info/pt/{pt}/tid/{tid}/nid/{nid}' ,`urls_url6` = '/novel/info/pt/{pt}/tid/{tid}/nid/{nid}' WHERE `urls_id` = '3';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=menu&pt={pt}&tid={tid}&nid={nid}&page={page}' ,`urls_url4` = '/?path=/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' ,`urls_url5` = '/index.php/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' ,`urls_url6` = '/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' WHERE `urls_id` = '4';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=read&pt={pt}&tid={tid}&nid={nid}&cid={cid}' ,`urls_url4` = '/?path=/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}' ,`urls_url5` = '/index.php/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}' ,`urls_url6` = '/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}' WHERE `urls_id` = '5';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=topindex&pt={pt}' ,`urls_url4` = '/?path=/novel/topindex/pt/{pt}' ,`urls_url5` = '/index.php/novel/topindex/pt/{pt}' ,`urls_url6` = '/novel/topindex/pt/{pt}' WHERE `urls_id` = '6';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=search&pt={pt}&type={type}&key={key}&page={page}' ,`urls_url4` = '/?path=/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}' ,`urls_url5` = '/index.php/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}' ,`urls_url6` = '/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}' WHERE `urls_id` = '7';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=toplist&pt={pt}&tid={tid}&type={type}&page={page}' ,`urls_url4` = '/?path=/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}' ,`urls_url5` = '/index.php/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}' ,`urls_url6` = '/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}' WHERE `urls_id` = '8';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=login&pt={pt}' ,`urls_url4` = '/?path=/user/login/pt/{pt}' ,`urls_url5` = '/index.php/user/login/pt/{pt}' ,`urls_url6` = '/user/login/pt/{pt}' WHERE `urls_id` = '9';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=reg&pt={pt}' ,`urls_url4` = '/?path=/user/reg/pt/{pt}' ,`urls_url5` = '/index.php/user/reg/pt/{pt}' ,`urls_url6` = '/user/reg/pt/{pt}' WHERE `urls_id` = '10';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=getpsw&pt={pt}' ,`urls_url4` = '/?path=/user/getpsw/pt/{pt}' ,`urls_url5` = '/index.php/user/getpsw/pt/{pt}' ,`urls_url6` = '/user/getpsw/pt/{pt}' WHERE `urls_id` = '11';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=home&pt={pt}' ,`urls_url4` = '/?path=/user/home/pt/{pt}' ,`urls_url5` = '/index.php/user/home/pt/{pt}' ,`urls_url6` = '/user/home/pt/{pt}' WHERE `urls_id` = '12';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=exit&pt={pt}' ,`urls_url4` = '/?path=/user/exit/pt/{pt}' ,`urls_url5` = '/index.php/user/exit/pt/{pt}' ,`urls_url6` = '/user/exit/pt/{pt}' WHERE `urls_id` = '13';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=basic&pt={pt}' ,`urls_url4` = '/?path=/user/basic/pt/{pt}' ,`urls_url5` = '/index.php/user/basic/pt/{pt}' ,`urls_url6` = '/user/basic/pt/{pt}' WHERE `urls_id` = '14';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=attribute&pt={pt}' ,`urls_url4` = '/?path=/user/attribute/pt/{pt}' ,`urls_url5` = '/index.php/user/attribute/pt/{pt}' ,`urls_url6` = '/user/attribute/pt/{pt}' WHERE `urls_id` = '16';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=head&pt={pt}' ,`urls_url4` = '/?path=/user/head/pt/{pt}' ,`urls_url5` = '/index.php/user/head/pt/{pt}' ,`urls_url6` = '/user/head/pt/{pt}' WHERE `urls_id` = '18';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=uppsw&pt={pt}' ,`urls_url4` = '/?path=/user/uppsw/pt/{pt}' ,`urls_url5` = '/index.php/user/uppsw/pt/{pt}' ,`urls_url6` = '/user/uppsw/pt/{pt}' WHERE `urls_id` = '19';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=varemail&pt={pt}' ,`urls_url4` = '/?path=/user/varemail/pt/{pt}' ,`urls_url5` = '/index.php/user/varemail/pt/{pt}' ,`urls_url6` = '/user/varemail/pt/{pt}' WHERE `urls_id` = '20';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=coll&module={module}&type={type}&page={page}&pt={pt}' ,`urls_url4` = '/?path=/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}' ,`urls_url5` = '/index.php/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}' ,`urls_url6` = '/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}' WHERE `urls_id` = '22';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=replay&pt={pt}&tid={tid}&nid={nid}&page={page}' ,`urls_url4` = '/?path=/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' ,`urls_url5` = '/index.php/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' ,`urls_url6` = '/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}' WHERE `urls_id` = '24';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=diy&file=diy&pt={pt}&did={did}' ,`urls_url4` = '/?path=/diy/diy/pt/{pt}/did/{did}' ,`urls_url5` = '/index.php/diy/diy/pt/{pt}/did/{did}' ,`urls_url6` = '/diy/diy/pt/{pt}/did/{did}' WHERE `urls_id` = '26';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=msglist&pt={pt}&page={page}' ,`urls_url4` = '/?path=/user/msglist/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/user/msglist/pt/{pt}/page/{page}' ,`urls_url6` = '/user/msglist/pt/{pt}/page/{page}' WHERE `urls_id` = '27';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=msg&pt={pt}&mid={mid}' ,`urls_url4` = '/?path=/user/msg/pt/{pt}/mid/{mid}' ,`urls_url5` = '/index.php/user/msg/pt/{pt}/mid/{mid}' ,`urls_url6` = '/user/msg/pt/{pt}/mid/{mid}' WHERE `urls_id` = '28';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=index&pt={pt}' ,`urls_url4` = '/?path=/author/index/pt/{pt}' ,`urls_url5` = '/index.php/author/index/pt/{pt}' ,`urls_url6` = '/author/index/pt/{pt}' WHERE `urls_id` = '29';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_noveledit&pt={pt}&nid={nid}' ,`urls_url4` = '/?path=/author/novel_noveledit/pt/{pt}/nid/{nid}' ,`urls_url5` = '/index.php/author/novel_noveledit/pt/{pt}/nid/{nid}' ,`urls_url6` = '/author/novel_noveledit/pt/{pt}/nid/{nid}' WHERE `urls_id` = '30';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_novellist&pt={pt}&page={page}' ,`urls_url4` = '/?path=/author/novel_novellist/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/author/novel_novellist/pt/{pt}/page/{page}' ,`urls_url6` = '/author/novel_novellist/pt/{pt}/page/{page}' WHERE `urls_id` = '31';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_volumelist&pt={pt}&nid={nid}&page={page}' ,`urls_url4` = '/?path=/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}' ,`urls_url5` = '/index.php/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}' ,`urls_url6` = '/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}' WHERE `urls_id` = '32';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=createchapter&pt={pt}&cid={cid}' ,`urls_url4` = '/?path=/author/createchapter/pt/{pt}/cid/{cid}' ,`urls_url5` = '/index.php/author/createchapter/pt/{pt}/cid/{cid}' ,`urls_url6` = '/author/createchapter/pt/{pt}/cid/{cid}' WHERE `urls_id` = '33';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_draftlist&nid={nid}&page={page}&pt={pt}' ,`urls_url4` = '/?path=/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}' ,`urls_url5` = '/index.php/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}' ,`urls_url6` = '/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}' WHERE `urls_id` = '34';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_draftedit&pt={pt}&nid={nid}&did={did}' ,`urls_url4` = '/?path=/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}' ,`urls_url5` = '/index.php/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}' ,`urls_url6` = '/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}' WHERE `urls_id` = '36';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_chapterlist&pt={pt}&nid={nid}&page={page}' ,`urls_url4` = '/?path=/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}' ,`urls_url5` = '/index.php/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}' ,`urls_url6` = '/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}' WHERE `urls_id` = '37';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=zt&file=zt&pt={pt}&zid={zid}' ,`urls_url4` = '/?path=/zt/zt/pt/{pt}/zid/{zid}' ,`urls_url5` = '/index.php/zt/zt/pt/{pt}/zid/{zid}' ,`urls_url6` = '/zt/zt/pt/{pt}/zid/{zid}' WHERE `urls_id` = '38';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=zt&file=type&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/zt/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/zt/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/zt/type/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '39';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=fvistlist&pt={pt}&uid={uid}&page={page}' ,`urls_url4` = '/?path=/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}' ,`urls_url5` = '/index.php/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}' ,`urls_url6` = '/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}' WHERE `urls_id` = '42';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=basic&pt={pt}' ,`urls_url4` = '/?path=/author/basic/pt/{pt}' ,`urls_url5` = '/index.php/author/basic/pt/{pt}' ,`urls_url6` = '/author/basic/pt/{pt}' WHERE `urls_id` = '43';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=incomechapter&pt={pt}&page={page}' ,`urls_url4` = '/?path=/author/incomechapter/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/author/incomechapter/pt/{pt}/page/{page}' ,`urls_url6` = '/author/incomechapter/pt/{pt}/page/{page}' WHERE `urls_id` = '44';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=incomedashang&pt={pt}&page={page}' ,`urls_url4` = '/?path=/author/incomedashang/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/author/incomedashang/pt/{pt}/page/{page}' ,`urls_url6` = '/author/incomedashang/pt/{pt}/page/{page}' WHERE `urls_id` = '45';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=mentionapply&pt={pt}' ,`urls_url4` = '/?path=/author/mentionapply/pt/{pt}' ,`urls_url5` = '/index.php/author/mentionapply/pt/{pt}' ,`urls_url6` = '/author/mentionapply/pt/{pt}' WHERE `urls_id` = '46';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=mentionrecord&pt={pt}&page={page}' ,`urls_url4` = '/?path=/author/mentionrecord/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/author/mentionrecord/pt/{pt}/page/{page}' ,`urls_url6` = '/author/mentionrecord/pt/{pt}/page/{page}' WHERE `urls_id` = '47';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=type&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/article/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/article/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/article/type/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '48';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=article&pt={pt}&tid={tid}&aid={aid}' ,`urls_url4` = '/?path=/article/article/pt/{pt}/tid/{tid}/aid/{aid}' ,`urls_url5` = '/index.php/article/article/pt/{pt}/tid/{tid}/aid/{aid}' ,`urls_url6` = '/article/article/pt/{pt}/tid/{tid}/aid/{aid}' WHERE `urls_id` = '49';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=search&pt={pt}&key={key}&type={type}&page={page}' ,`urls_url4` = '/?path=/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url5` = '/index.php/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url6` = '/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}' WHERE `urls_id` = '50';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=replay&pt={pt}&tid={tid}&aid={aid}&page={page}' ,`urls_url4` = '/?path=/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}' ,`urls_url5` = '/index.php/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}' ,`urls_url6` = '/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}' WHERE `urls_id` = '51';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=index&pt={pt}' ,`urls_url4` = '/?path=/novel/index/pt/{pt}' ,`urls_url5` = '/index.php/novel/index/pt/{pt}' ,`urls_url6` = '/novel/index/pt/{pt}' WHERE `urls_id` = '52';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=message&file=add&pt={pt}' ,`urls_url4` = '/?path=/message/add/pt/{pt}' ,`urls_url5` = '/index.php/message/add/pt/{pt}' ,`urls_url6` = '/message/add/pt/{pt}' WHERE `urls_id` = '54';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=sign&pt={pt}' ,`urls_url4` = '/?path=/user/sign/pt/{pt}' ,`urls_url5` = '/index.php/user/sign/pt/{pt}' ,`urls_url6` = '/user/sign/pt/{pt}' WHERE `urls_id` = '55';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=index&pt={pt}' ,`urls_url4` = '/?path=/bbs/index/pt/{pt}' ,`urls_url5` = '/index.php/bbs/index/pt/{pt}' ,`urls_url6` = '/bbs/index/pt/{pt}' WHERE `urls_id` = '56';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=bbs&pt={pt}&tid={tid}&bid={bid}&page={page}' ,`urls_url4` = '/?path=/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}' ,`urls_url5` = '/index.php/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}' ,`urls_url6` = '/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}' WHERE `urls_id` = '57';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=type&pt={pt}&tid={tid}' ,`urls_url4` = '/?path=/bbs/type/pt/{pt}/tid/{tid}' ,`urls_url5` = '/index.php/bbs/type/pt/{pt}/tid/{tid}' ,`urls_url6` = '/bbs/type/pt/{pt}/tid/{tid}' WHERE `urls_id` = '58';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=list&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/bbs/list/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/bbs/list/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/bbs/list/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '59';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=tindex&pt={pt}&tid={tid}' ,`urls_url4` = '/?path=/article/tindex/pt/{pt}/tid/{tid}' ,`urls_url5` = '/index.php/article/tindex/pt/{pt}/tid/{tid}' ,`urls_url6` = '/article/tindex/pt/{pt}/tid/{tid}' WHERE `urls_id` = '83';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=post&pt={pt}&tid={tid}&bid={bid}' ,`urls_url4` = '/?path=/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}' ,`urls_url5` = '/index.php/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}' ,`urls_url6` = '/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}' WHERE `urls_id` = '61';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=link&file=index&pt={pt}' ,`urls_url4` = '/?path=/link/index/pt/{pt}' ,`urls_url5` = '/index.php/link/index/pt/{pt}' ,`urls_url6` = '/link/index/pt/{pt}' WHERE `urls_id` = '62';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=link&file=show&pt={pt}&tid={tid}&lid={lid}' ,`urls_url4` = '/?path=/link/show/pt/{pt}/tid/{tid}/lid/{lid}' ,`urls_url5` = '/index.php/link/show/pt/{pt}/tid/{tid}/lid/{lid}' ,`urls_url6` = '/link/show/pt/{pt}/tid/{tid}/lid/{lid}' WHERE `urls_id` = '63';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=link&file=click&lid={lid}&t={t}' ,`urls_url4` = '/?path=/link/click/lid/{lid}/t/{t}' ,`urls_url5` = '/index.php/link/click/lid/{lid}/t/{t}' ,`urls_url6` = '/link/click/lid/{lid}/t/{t}' WHERE `urls_id` = '64';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=link&file=type&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/link/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/link/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/link/type/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '65';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=link&file=join&pt={pt}' ,`urls_url4` = '/?path=/link/join/pt/{pt}' ,`urls_url5` = '/index.php/link/join/pt/{pt}' ,`urls_url6` = '/link/join/pt/{pt}' WHERE `urls_id` = '66';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=app&file=type&pt={pt}&tid={tid}&lid={lid}&pid={pid}&cid={cid}&ot={ot}&page={page}' ,`urls_url4` = '/?path=/app/type/pt/{pt}/tid/{tid}/lid/{lid}/pid/{pid}/cid/{cid}/ot/{ot}/page/{page}' ,`urls_url5` = '/index.php/app/type/pt/{pt}/tid/{tid}/lid/{lid}/pid/{pid}/cid/{cid}/ot/{ot}/page/{page}' ,`urls_url6` = '/app/type/pt/{pt}/tid/{tid}/lid/{lid}/pid/{pid}/cid/{cid}/ot/{ot}/page/{page}' WHERE `urls_id` = '67';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=app&file=app&pt={pt}&tid={tid}&aid={aid}' ,`urls_url4` = '/?path=/app/app/pt/{pt}/tid/{tid}/aid/{aid}' ,`urls_url5` = '/index.php/app/app/pt/{pt}/tid/{tid}/aid/{aid}' ,`urls_url6` = '/app/app/pt/{pt}/tid/{tid}/aid/{aid}' WHERE `urls_id` = '68';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=app&file=index&pt={pt}' ,`urls_url4` = '/?path=/app/index/pt/{pt}' ,`urls_url5` = '/index.php/app/index/pt/{pt}' ,`urls_url6` = '/app/index/pt/{pt}' WHERE `urls_id` = '69';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=down&file=down&pt={pt}&module={module}&fid={fid}&cid={cid}' ,`urls_url4` = '/?path=/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}' ,`urls_url5` = '/index.php/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}' ,`urls_url6` = '/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}' WHERE `urls_id` = '70';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=app&file=search&pt={pt}&type={type}&key={key}&page={page}' ,`urls_url4` = '/?path=/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}' ,`urls_url5` = '/index.php/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}' ,`urls_url6` = '/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}' WHERE `urls_id` = '71';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=article&file=index&pt={pt}' ,`urls_url4` = '/?path=/article/index/pt/{pt}' ,`urls_url5` = '/index.php/article/index/pt/{pt}' ,`urls_url6` = '/article/index/pt/{pt}' WHERE `urls_id` = '72';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=bbs&file=search&pt={pt}&key={key}&type={type}&page={page}' ,`urls_url4` = '/?path=/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url5` = '/index.php/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url6` = '/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}' WHERE `urls_id` = '73';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=about&file=type&pt={pt}&tid={tid}' ,`urls_url4` = '/?path=/about/type/pt/{pt}/tid/{tid}' ,`urls_url5` = '/index.php/about/type/pt/{pt}/tid/{tid}' ,`urls_url6` = '/about/type/pt/{pt}/tid/{tid}' WHERE `urls_id` = '74';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=about&file=about&pt={pt}&aid={aid}' ,`urls_url4` = '/?path=/about/about/pt/{pt}/aid/{aid}' ,`urls_url5` = '/index.php/about/about/pt/{pt}/aid/{aid}' ,`urls_url6` = '/about/about/pt/{pt}/aid/{aid}' WHERE `urls_id` = '75';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=type&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/picture/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/picture/type/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/picture/type/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '76';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=picture&pt={pt}&tid={tid}&pid={pid}&page={page}' ,`urls_url4` = '/?path=/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}' ,`urls_url5` = '/index.php/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}' ,`urls_url6` = '/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}' WHERE `urls_id` = '77';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=search&pt={pt}&key={key}&type={type}&page={page}' ,`urls_url4` = '/?path=/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url5` = '/index.php/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}' ,`urls_url6` = '/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}' WHERE `urls_id` = '78';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=replay&pt={pt}&tid={tid}&cid={cid}&page={page}' ,`urls_url4` = '/?path=/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}' ,`urls_url5` = '/index.php/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}' ,`urls_url6` = '/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}' WHERE `urls_id` = '79';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=toplist&pt={pt}&tid={tid}&page={page}' ,`urls_url4` = '/?path=/picture/toplist/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url5` = '/index.php/picture/toplist/pt/{pt}/tid/{tid}/page/{page}' ,`urls_url6` = '/picture/toplist/pt/{pt}/tid/{tid}/page/{page}' WHERE `urls_id` = '80';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=signlist&pt={pt}&page={page}' ,`urls_url4` = '/?path=/user/signlist/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/user/signlist/pt/{pt}/page/{page}' ,`urls_url6` = '/user/signlist/pt/{pt}/page/{page}' WHERE `urls_id` = '81';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=fhome&pt={pt}&uid={uid}' ,`urls_url4` = '/?path=/user/fhome/pt/{pt}/uid/{uid}' ,`urls_url5` = '/index.php/user/fhome/pt/{pt}/uid/{uid}' ,`urls_url6` = '/user/fhome/pt/{pt}/uid/{uid}' WHERE `urls_id` = '23';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=apilogin' ,`urls_url4` = '/?path=/user/apilogin' ,`urls_url5` = '/index.php/user/apilogin' ,`urls_url6` = '/user/apilogin' WHERE `urls_id` = '82';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=picture&file=index&pt={pt}' ,`urls_url4` = '/?path=/picture/index/pt/{pt}' ,`urls_url5` = '/index.php/picture/index/pt/{pt}' ,`urls_url6` = '/picture/index/pt/{pt}' WHERE `urls_id` = '84';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=fcoll&module={module}&type={type}&page={page}&pt={pt}&uid={uid}' ,`urls_url4` = '/?path=/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}' ,`urls_url5` = '/index.php/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}' ,`urls_url6` = '/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}' WHERE `urls_id` = '85';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=tindex&pt={pt}&tid={tid}' ,`urls_url4` = '/?path=/novel/tindex/pt/{pt}/tid/{tid}' ,`urls_url5` = '/index.php/novel/tindex/pt/{pt}/tid/{tid}' ,`urls_url6` = '/novel/tindex/pt/{pt}/tid/{tid}' WHERE `urls_id` = '86';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=apply&pt={pt}' ,`urls_url4` = '/?path=/author/apply/pt/{pt}' ,`urls_url5` = '/index.php/author/apply/pt/{pt}' ,`urls_url6` = '/author/apply/pt/{pt}' WHERE `urls_id` = '87';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=agreement&pt={pt}' ,`urls_url4` = '/?path=/author/agreement/pt/{pt}' ,`urls_url5` = '/index.php/author/agreement/pt/{pt}' ,`urls_url6` = '/author/agreement/pt/{pt}' WHERE `urls_id` = '88';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_volumeedit&pt={pt}&nid={nid}&vid={vid}' ,`urls_url4` = '/?path=/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}' ,`urls_url5` = '/index.php/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}' ,`urls_url6` = '/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}' WHERE `urls_id` = '89';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=article_articlelist&pt={pt}&page={page}' ,`urls_url4` = '/?path=/author/article_articlelist/pt/{pt}/page/{page}' ,`urls_url5` = '/index.php/author/article_articlelist/pt/{pt}/page/{page}' ,`urls_url6` = '/author/article_articlelist/pt/{pt}/page/{page}' WHERE `urls_id` = '90';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=article_draftedit&did={did}&pt={pt}' ,`urls_url4` = '/?path=/author/article_draftedit/did/{did}/pt/{pt}' ,`urls_url5` = '/index.php/author/article_draftedit/did/{did}/pt/{pt}' ,`urls_url6` = '/author/article_draftedit/did/{did}/pt/{pt}' WHERE `urls_id` = '91';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=article_draftlist&page={page}&pt={pt}' ,`urls_url4` = '/?path=/author/article_draftlist/page/{page}/pt/{pt}' ,`urls_url5` = '/index.php/author/article_draftlist/page/{page}/pt/{pt}' ,`urls_url6` = '/author/article_draftlist/page/{page}/pt/{pt}' WHERE `urls_id` = '92';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=article_articleedit&id={id}&pt={pt}' ,`urls_url4` = '/?path=/author/article_articleedit/id/{id}/pt/{pt}' ,`urls_url5` = '/index.php/author/article_articleedit/id/{id}/pt/{pt}' ,`urls_url6` = '/author/article_articleedit/id/{id}/pt/{pt}' WHERE `urls_id` = '93';
UPDATE `{$urlsTable}` SET `urls_url3` = '/wmcms/module/sitemap/index.php?type=html' ,`urls_url4` = '/wmcms/module/sitemap/index.php?type=html' ,`urls_url5` = '/wmcms/module/sitemap/index.php?type=html' ,`urls_url6` = '/wmcms/module/sitemap/index.php?type=html' WHERE `urls_id` = '94';
UPDATE `{$urlsTable}` SET `urls_url3` = '/wmcms/module/sitemap/index.php?type=xml' ,`urls_url4` = '/wmcms/module/sitemap/index.php?type=xml' ,`urls_url5` = '/wmcms/module/sitemap/index.php?type=xml' ,`urls_url6` = '/wmcms/module/sitemap/index.php?type=xml' WHERE `urls_id` = '95';
UPDATE `{$urlsTable}` SET `urls_url3` = '/wmcms/module/sitemap/index.php?type=rss' ,`urls_url4` = '/wmcms/module/sitemap/index.php?type=rss' ,`urls_url5` = '/wmcms/module/sitemap/index.php?type=rss' ,`urls_url6` = '/wmcms/module/sitemap/index.php?type=rss' WHERE `urls_id` = '96';
UPDATE `{$urlsTable}` SET `urls_url3` = '/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}' ,`urls_url4` = '/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}' ,`urls_url5` = '/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}' ,`urls_url6` = '/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}' WHERE `urls_id` = '97';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=charge&pt={pt}' ,`urls_url4` = '/?path=/user/charge/pt/{pt}' ,`urls_url5` = '/index.php/user/charge/pt/{pt}' ,`urls_url6` = '/user/charge/pt/{pt}' WHERE `urls_id` = '98';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=author&file=novel_incomelist&type={type}&page={page}&pt={pt}' ,`urls_url4` = '/?path=/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}' ,`urls_url5` = '/index.php/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}' ,`urls_url6` = '/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}' WHERE `urls_id` = '99';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=cash_apply&pt={pt}' ,`urls_url4` = '/?path=/user/cash_apply/pt/{pt}' ,`urls_url5` = '/index.php/user/cash_apply/pt/{pt}' ,`urls_url6` = '/user/cash_apply/pt/{pt}' WHERE `urls_id` = '100';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=cash_list&page={page}&pt={pt}' ,`urls_url4` = '/?path=/user/cash_list/page/{page}/pt/{pt}' ,`urls_url5` = '/index.php/user/cash_list/page/{page}/pt/{pt}' ,`urls_url6` = '/user/cash_list/page/{page}/pt/{pt}' WHERE `urls_id` = '101';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=about&file=tindex&pt={pt}&tid={tid}' ,`urls_url4` = '/?path=/about/tindex/pt/{pt}/tid/{tid}' ,`urls_url5` = '/index.php/about/tindex/pt/{pt}/tid/{tid}' ,`urls_url6` = '/about/tindex/pt/{pt}/tid/{tid}' WHERE `urls_id` = '102';
UPDATE `{$urlsTable}` SET `urls_url3` = '/wmcms/module/sitemap/index.php?type=site' ,`urls_url4` = '/wmcms/module/sitemap/index.php?type=site' ,`urls_url5` = '/wmcms/module/sitemap/index.php?type=site' ,`urls_url6` = '/wmcms/module/sitemap/index.php?type=site' WHERE `urls_id` = '103';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=type&pt={pt}&tid={tid}&page={page}&process={process}&word={word}&chapter={chapter}&copy={copy}&cost={cost}&letter={letter}&order={order}' ,`urls_url4` = '/?path=/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}' ,`urls_url5` = '/index.php/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}' ,`urls_url6` = '/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}' WHERE `urls_id` = '104';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=index_boy&pt={pt}' ,`urls_url4` = '/?path=/novel/index_boy/pt/{pt}' ,`urls_url5` = '/index.php/novel/index_boy/pt/{pt}' ,`urls_url6` = '/novel/index_boy/pt/{pt}' WHERE `urls_id` = '105';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=novel&file=index_girl&pt={pt}' ,`urls_url4` = '/?path=/novel/index_girl/pt/{pt}' ,`urls_url5` = '/index.php/novel/index_girl/pt/{pt}' ,`urls_url6` = '/novel/index_girl/pt/{pt}' WHERE `urls_id` = '106';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=read&pt={pt}&module={module}&page={page}' ,`urls_url4` = '/?path=/user/read/pt/{pt}/module/{module}/page/{page}' ,`urls_url5` = '/index.php/user/read/pt/{pt}/module/{module}/page/{page}' ,`urls_url6` = '/user/read/pt/{pt}/module/{module}/page/{page}' WHERE `urls_id` = '107';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=charge_code&pt={pt}&code={code}&sn={sn}' ,`urls_url4` = '/?path=/user/charge_code/pt/{pt}/code/{code}/sn/{sn}' ,`urls_url5` = '/index.php/user/charge_code/pt/{pt}/code/{code}/sn/{sn}' ,`urls_url6` = '/user/charge_code/pt/{pt}/code/{code}/sn/{sn}' WHERE `urls_id` = '108';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=user&file=charge_success&pt={pt}' ,`urls_url4` = '/?path=/user/charge_success/pt/{pt}' ,`urls_url5` = '/index.php/user/charge_success/pt/{pt}' ,`urls_url6` = '/user/charge_success/pt/{pt}' WHERE `urls_id` = '109';
UPDATE `{$urlsTable}` SET `urls_url3` = '/?module=replay&file=list&pt={pt}&module={module}&page={page}' ,`urls_url4` = '/?path=/replay/list/pt/{pt}/module/{module}/page/{page}' ,`urls_url5` = '/index.php/replay/list/pt/{pt}/module/{module}/page/{page}' ,`urls_url6` = '/replay/list/pt/{pt}/module/{module}/page/{page}' WHERE `urls_id` = '110';");
?>