<?php

                /***************************************************************************
		                                simplechinese.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: simplechinese.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/	 
include_once('language/langcommon.php');

class xxbLanguage extends langcommon{
	
	private $menu = array();
	protected $menus = array();
	protected $xxbLang = array();
	
	public static function makeObj($obj){
		if(self::state('simplechinese')){
			return new xxbLanguage($obj);
		}else{
			exit('Just can make only one xxbLanguage(simplechinese) object!');
		}
	}
	
	private function xxbLanguage($obj){
		parent::__construct($obj);
		
		$menu[0]   = '首页';
		$menu[1]   = '帮助';
		$menu[2]   = '登录';
		$menu[3]   = '退出';
		$menu[4]   = '公文箱';
		$menu[5]   = '收文箱';
		$menu[6]   = '发文箱';
		$menu[7]   = '回收站';
		$menu[8]   = '常见问答';
		$menu[9]   = '使用手册';
		$menu[10]  = '档案箱';
		
		$this->xxbLang['back']     = '返回';
		$this->xxbLang['edit']     = '操作';
		$this->xxbLang['ofile']    = '公文';
		$this->xxbLang['search']   = '搜索';	
		
		$this->xxbLang['newoffice']= '新建公文';
		
		$this->xxbLang['doctitle'] = '内蒙古自治区呼和浩特市人民政府&nbsp;--&nbsp;文件系统0.1版';
		
		$this->xxbLang['ge'] = '个';
		$this->xxbLang['ok'] = '确定';
		
		$this->xxbLang['year']  = '年';
 		$this->xxbLang['month'] = '月';
 		$this->xxbLang['day']   = '日';
 		
 		$this->xxbLang['Mon'] = '星期一';
 		$this->xxbLang['Tue'] = '星期二';
 		$this->xxbLang['Wed'] = '星期三';
 		$this->xxbLang['Thu'] = '星期四';
 		$this->xxbLang['Fri'] = '星期五';
 		$this->xxbLang['Sat'] = '星期六';
 		$this->xxbLang['Sun'] = '星期日';
 		
 		$this->xxbLang['add']     = '添加';
 		$this->xxbLang['addall']  = '全部添加';
 		$this->xxbLang['delall']  = '全部删除';
 		$this->xxbLang['resetreceiver']  = '重新指定';
 		$this->xxbLang['view']    = '浏览';
 		$this->xxbLang['preview'] = '预览';
 		$this->xxbLang['rewrite'] = '重写';
 		$this->xxbLang['save']    = '保存';
 		$this->xxbLang['new']     = '新建';
 		$this->xxbLang['readysend']    = '发文';
 		$this->xxbLang['send']    = '发送';
 		$this->xxbLang['modify']  = '修改';
 		$this->xxbLang['delete']  = '删除';
 		$this->xxbLang['undo']    = '恢复';
 		$this->xxbLang['receiver']  = '收文单位';
		$this->xxbLang['senduser']  = '发文单位';
 		$this->xxbLang['receivertitle']  = '指定 <' . $this->xxbLang['receiver'] . '>';
 		$this->xxbLang['officefile']   = $menu[4];
 		$this->xxbLang['trash']        = $menu[7];
 		$this->xxbLang['sendedoffice'] = $menu[6];
		$this->xxbLang['receivedoffice']= $menu[5];
		$this->xxbLang['officerecords'] = $menu[10];
		$this->xxbLang['fixed']        = '归档';
		$this->xxbLang['readed']       = $this->xxbLang['fixed'];
		$this->xxbLang['unreceivername'] = '单位列表';
		$this->xxbLang['receivername'] = '收文单位';
 		
 		$this->xxbLang['foot1'] = '内蒙古自治区呼和浩特市人民政府';
 		$this->xxbLang['foot2'] = '信息化工作办公室';
 		$this->xxbLang['foot3'] = '地址：新华东街一号党政机关大楼后楼315室/电话：4606572/电子邮件：brgd@nm114.net';
 		
 		$this->xxbLang['userid']    = '代码:';
 		$this->xxbLang['userpass']  = '密码:';
 		$this->xxbLang['userlogin'] = $menu[2];
 		$this->xxbLang['userreset'] = '清空';
 		$this->xxbLang['userfail']  = $this->xxbLang['userid'] . '&nbsp;或&nbsp;' . $this->xxbLang['userpass'] . '&nbsp;错误！';
 		
 		$this->xxbLang['currentuser'] = ' 您好！ ';
 		
 		$this->xxbLang['idorder']     = '序号';
 		$this->xxbLang['name']        = '名称';
 		$this->xxbLang['description'] = '说明';
 		
 		$this->xxbLang['officeillegal']    = '抱歉!您执行了无效操作！';
 		$this->xxbLang['nofile']           = '抱歉!没有找到您正在查询的文件！';
 		$this->xxbLang['selectofficetype'] = '请您点击选择要新建的公文种类名称！';
 		$this->xxbLang['selecttype']       = '文种';
 		$this->xxbLang['title']            = '标题';
 		$this->xxbLang['content']          = '内容';

		$this->xxbLang['lurgencydegree']    = '缓急';
		$this->xxbLang['urgencydegree'][0]  = '普通';
 		$this->xxbLang['urgencydegree'][1]  = '急件';
 		$this->xxbLang['urgencydegree'][2]  = '特急';
 		
 		$this->xxbLang['lasttime']      = '期限';
 		$this->xxbLang['bureauname']    = '发文机关 文件';
 		$this->xxbLang['agencyname']    = '发文机关代字';
 		$this->xxbLang['oyear']         = '年份';
 		$this->xxbLang['ordernum']      = '序号';
 		$this->xxbLang['noticetitle']   = '发文行政机关 关于 事由 的通知';
 		$this->xxbLang['noticecontent'] =  '受文机关：
	    通知的起因缘由，以及写明通知的背景和依据。
	    通知事项。
	    结尾视内容而异。';
	
		$this->xxbLang['accessories'] =  '1,附件名 2,附件名';
 		
 		$this->xxbLang['notice']   = '通知';
 		$this->xxbLang['hao']      = '号';
 		$this->xxbLang['fujian']   = '附件：';
 		$this->xxbLang['fuzhu']    = '附注';
 		$this->xxbLang['zhutici']  = '主题词：';
 		$this->xxbLang['zhutici2'] = '主题词 主题词 主题词 主题词 主题词';
 		$this->xxbLang['chaosong'] = '抄&nbsp;  送：';
 		$this->xxbLang['chaosong2']= '抄送机关，抄送机关，抄送机关，抄送机关，抄送机关';
 		$this->xxbLang['yinfajiguan'] = '印发机关';
 		$this->xxbLang['yinfa']    = '印发';
 		
 		$this->xxbLang['pubbureau']  = '创建单位';
 		$this->xxbLang['pubtime']  = '创建日期';
 		$this->xxbLang['deltime']  = '删除日期';
 		$this->xxbLang['sentime']  = '发送日期';
 		
 		$this->xxbLang['secretlevel'][0] = '普通';
 		$this->xxbLang['secretlevel'][1] = '秘密';
 		$this->xxbLang['secretlevel'][2] = '机密';
 		$this->xxbLang['secretlevel'][3] = '绝密';
 		 		
 		$this->xxbLang['officeundofailed']    = '抱歉!无法从 < ' . $menu[7] . ' > ' . $this->xxbLang['undo'] . '此公文！';
 		$this->xxbLang['officeunsendfailed']  = '抱歉!无法从 < ' . $menu[6] . ' > ' . $this->xxbLang['undo'] . '此公文！';
 		$this->xxbLang['officecreatedfail'] = '抱歉!此公文标题已存在,无法保存！';
 		$this->xxbLang['officemodifiedfail'] = '抱歉!此公文不存在,无法修改！';
 		$this->xxbLang['noofficefile']  = '您新建的公文不存在！';
 		$this->xxbLang['nodeletedofficefile'] = '您已删除的公文不存在！';
 		$this->xxbLang['nosendedofficefile']  = '您准备要发送的公文不存在！';
 		$this->xxbLang['noreceivededofficefile']  = '您已接收到的新公文不存在！';
		$this->xxbLang['noofficerecordsfile']  = '公文档案不存在！';
 		$this->xxbLang['ifsavenew']     = '确定要保存此新建的公文吗?';
 		$this->xxbLang['ifsaveold']     = '确定要保存对此公文的修改吗?';
 		$this->xxbLang['ifsend']        = '确定要将此公文移动到 < ' . $menu[6] . ' > 吗?';
 		$this->xxbLang['ifdelete']      = '确定要将此公文移动到 < ' . $menu[7] . ' > 吗?';
 		$this->xxbLang['ifdeleteever']  = '警告!确定要彻底从系统删除此公文吗?';
		$this->xxbLang['ifsavenames']   = '确定要保存此名单吗?';
		$this->xxbLang['rediconfirm']   = '确定要将此公文移动到 < ' . $menu[10] . ' > 吗?归档后此公文只能被查阅,其它一切操作都将被禁止！ ';
 		$this->xxbLang['officesendfailed'] = '抱歉!此公文无法发送到 < ' . $menu[6] . ' > ！';
 		$this->xxbLang['officedeletefailed']  = '抱歉!此公文不存在,无法删除！';
 		$this->xxbLang['officedeleteeverfailed']  = '抱歉!此公文无法被彻底删除！';
 		$this->xxbLang['officemodifyfailed']      = '抱歉!无法编辑此公文！';
 		$this->xxbLang['officesetreceiverfailed'] = '抱歉!无法给此公文指定 < ' . $this->xxbLang['receiver'] . ' > ！';
		$this->xxbLang['officesetfixfailed'] = '抱歉!无法将此公文移动到 < ' . $menu[10] . ' > ！';
 		$this->xxbLang['setReceiOfficeFReadfailed'] = '抱歉!无法将此收到的公文移动到 < ' . $menu[10] . ' > ！';
		$this->xxbLang['checkscript']  = '<script language="javascript"><!-- Begin
	function allconfirm(string,aform){if(confirm(string)){
			aform.submit();}}function allsubmit(aform){aform.submit();}//  End --></script>';
 		$this->xxbLang['resetscript']  = '<script language="javascript"><!-- Begin
	function allreset(aform){aform.reset();}//  End --></script>';
		$this->xxbLang['delscript']  = '<script language="javascript"><!-- Begin
	function delconfirm(string,loca){if(confirm(string)){location=loca;}}//  End --></script>';
 		$this->setHelp();
 		$this->xxbLang['confirmscript']  = '<script language="javascript"><!-- Begin
	function rediconfirm(string,www){if(confirm(string)){location.replace(www)}}//  End --></script>';
 		$this->setHelp();
 		
 		$this->menus = $this->setMenus($menu);
	} 
	
	private function setHelp(){
		$this->xxbLang['help'] = '内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net  
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助内蒙古自治区呼和浩特市人民政府
地址：新华东街一号政府大楼附楼三层315室/电话：0471-4606572/电子邮件：brgd@nm14.net
信息化工作办公室制作 - 信息中心协助';
	}
}
?>