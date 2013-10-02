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
		
		$menu[0]   = '��ҳ';
		$menu[1]   = '����';
		$menu[2]   = '��¼';
		$menu[3]   = '�˳�';
		$menu[4]   = '������';
		$menu[5]   = '������';
		$menu[6]   = '������';
		$menu[7]   = '����վ';
		$menu[8]   = '�����ʴ�';
		$menu[9]   = 'ʹ���ֲ�';
		$menu[10]  = '������';
		
		$this->xxbLang['back']     = '����';
		$this->xxbLang['edit']     = '����';
		$this->xxbLang['ofile']    = '����';
		$this->xxbLang['search']   = '����';	
		
		$this->xxbLang['newoffice']= '�½�����';
		
		$this->xxbLang['doctitle'] = '���ɹ����������ͺ�������������&nbsp;--&nbsp;�ļ�ϵͳ0.1��';
		
		$this->xxbLang['ge'] = '��';
		$this->xxbLang['ok'] = 'ȷ��';
		
		$this->xxbLang['year']  = '��';
 		$this->xxbLang['month'] = '��';
 		$this->xxbLang['day']   = '��';
 		
 		$this->xxbLang['Mon'] = '����һ';
 		$this->xxbLang['Tue'] = '���ڶ�';
 		$this->xxbLang['Wed'] = '������';
 		$this->xxbLang['Thu'] = '������';
 		$this->xxbLang['Fri'] = '������';
 		$this->xxbLang['Sat'] = '������';
 		$this->xxbLang['Sun'] = '������';
 		
 		$this->xxbLang['add']     = '���';
 		$this->xxbLang['addall']  = 'ȫ�����';
 		$this->xxbLang['delall']  = 'ȫ��ɾ��';
 		$this->xxbLang['resetreceiver']  = '����ָ��';
 		$this->xxbLang['view']    = '���';
 		$this->xxbLang['preview'] = 'Ԥ��';
 		$this->xxbLang['rewrite'] = '��д';
 		$this->xxbLang['save']    = '����';
 		$this->xxbLang['new']     = '�½�';
 		$this->xxbLang['readysend']    = '����';
 		$this->xxbLang['send']    = '����';
 		$this->xxbLang['modify']  = '�޸�';
 		$this->xxbLang['delete']  = 'ɾ��';
 		$this->xxbLang['undo']    = '�ָ�';
 		$this->xxbLang['receiver']  = '���ĵ�λ';
		$this->xxbLang['senduser']  = '���ĵ�λ';
 		$this->xxbLang['receivertitle']  = 'ָ�� <' . $this->xxbLang['receiver'] . '>';
 		$this->xxbLang['officefile']   = $menu[4];
 		$this->xxbLang['trash']        = $menu[7];
 		$this->xxbLang['sendedoffice'] = $menu[6];
		$this->xxbLang['receivedoffice']= $menu[5];
		$this->xxbLang['officerecords'] = $menu[10];
		$this->xxbLang['fixed']        = '�鵵';
		$this->xxbLang['readed']       = $this->xxbLang['fixed'];
		$this->xxbLang['unreceivername'] = '��λ�б�';
		$this->xxbLang['receivername'] = '���ĵ�λ';
 		
 		$this->xxbLang['foot1'] = '���ɹ����������ͺ�������������';
 		$this->xxbLang['foot2'] = '��Ϣ�������칫��';
 		$this->xxbLang['foot3'] = '��ַ���»�����һ�ŵ������ش�¥��¥315��/�绰��4606572/�����ʼ���brgd@nm114.net';
 		
 		$this->xxbLang['userid']    = '����:';
 		$this->xxbLang['userpass']  = '����:';
 		$this->xxbLang['userlogin'] = $menu[2];
 		$this->xxbLang['userreset'] = '���';
 		$this->xxbLang['userfail']  = $this->xxbLang['userid'] . '&nbsp;��&nbsp;' . $this->xxbLang['userpass'] . '&nbsp;����';
 		
 		$this->xxbLang['currentuser'] = ' ���ã� ';
 		
 		$this->xxbLang['idorder']     = '���';
 		$this->xxbLang['name']        = '����';
 		$this->xxbLang['description'] = '˵��';
 		
 		$this->xxbLang['officeillegal']    = '��Ǹ!��ִ������Ч������';
 		$this->xxbLang['nofile']           = '��Ǹ!û���ҵ������ڲ�ѯ���ļ���';
 		$this->xxbLang['selectofficetype'] = '�������ѡ��Ҫ�½��Ĺ����������ƣ�';
 		$this->xxbLang['selecttype']       = '����';
 		$this->xxbLang['title']            = '����';
 		$this->xxbLang['content']          = '����';

		$this->xxbLang['lurgencydegree']    = '����';
		$this->xxbLang['urgencydegree'][0]  = '��ͨ';
 		$this->xxbLang['urgencydegree'][1]  = '����';
 		$this->xxbLang['urgencydegree'][2]  = '�ؼ�';
 		
 		$this->xxbLang['lasttime']      = '����';
 		$this->xxbLang['bureauname']    = '���Ļ��� �ļ�';
 		$this->xxbLang['agencyname']    = '���Ļ��ش���';
 		$this->xxbLang['oyear']         = '���';
 		$this->xxbLang['ordernum']      = '���';
 		$this->xxbLang['noticetitle']   = '������������ ���� ���� ��֪ͨ';
 		$this->xxbLang['noticecontent'] =  '���Ļ��أ�
	    ֪ͨ������Ե�ɣ��Լ�д��֪ͨ�ı��������ݡ�
	    ֪ͨ���
	    ��β�����ݶ��졣';
	
		$this->xxbLang['accessories'] =  '1,������ 2,������';
 		
 		$this->xxbLang['notice']   = '֪ͨ';
 		$this->xxbLang['hao']      = '��';
 		$this->xxbLang['fujian']   = '������';
 		$this->xxbLang['fuzhu']    = '��ע';
 		$this->xxbLang['zhutici']  = '����ʣ�';
 		$this->xxbLang['zhutici2'] = '����� ����� ����� ����� �����';
 		$this->xxbLang['chaosong'] = '��&nbsp;  �ͣ�';
 		$this->xxbLang['chaosong2']= '���ͻ��أ����ͻ��أ����ͻ��أ����ͻ��أ����ͻ���';
 		$this->xxbLang['yinfajiguan'] = 'ӡ������';
 		$this->xxbLang['yinfa']    = 'ӡ��';
 		
 		$this->xxbLang['pubbureau']  = '������λ';
 		$this->xxbLang['pubtime']  = '��������';
 		$this->xxbLang['deltime']  = 'ɾ������';
 		$this->xxbLang['sentime']  = '��������';
 		
 		$this->xxbLang['secretlevel'][0] = '��ͨ';
 		$this->xxbLang['secretlevel'][1] = '����';
 		$this->xxbLang['secretlevel'][2] = '����';
 		$this->xxbLang['secretlevel'][3] = '����';
 		 		
 		$this->xxbLang['officeundofailed']    = '��Ǹ!�޷��� < ' . $menu[7] . ' > ' . $this->xxbLang['undo'] . '�˹��ģ�';
 		$this->xxbLang['officeunsendfailed']  = '��Ǹ!�޷��� < ' . $menu[6] . ' > ' . $this->xxbLang['undo'] . '�˹��ģ�';
 		$this->xxbLang['officecreatedfail'] = '��Ǹ!�˹��ı����Ѵ���,�޷����棡';
 		$this->xxbLang['officemodifiedfail'] = '��Ǹ!�˹��Ĳ�����,�޷��޸ģ�';
 		$this->xxbLang['noofficefile']  = '���½��Ĺ��Ĳ����ڣ�';
 		$this->xxbLang['nodeletedofficefile'] = '����ɾ���Ĺ��Ĳ����ڣ�';
 		$this->xxbLang['nosendedofficefile']  = '��׼��Ҫ���͵Ĺ��Ĳ����ڣ�';
 		$this->xxbLang['noreceivededofficefile']  = '���ѽ��յ����¹��Ĳ����ڣ�';
		$this->xxbLang['noofficerecordsfile']  = '���ĵ��������ڣ�';
 		$this->xxbLang['ifsavenew']     = 'ȷ��Ҫ������½��Ĺ�����?';
 		$this->xxbLang['ifsaveold']     = 'ȷ��Ҫ����Դ˹��ĵ��޸���?';
 		$this->xxbLang['ifsend']        = 'ȷ��Ҫ���˹����ƶ��� < ' . $menu[6] . ' > ��?';
 		$this->xxbLang['ifdelete']      = 'ȷ��Ҫ���˹����ƶ��� < ' . $menu[7] . ' > ��?';
 		$this->xxbLang['ifdeleteever']  = '����!ȷ��Ҫ���״�ϵͳɾ���˹�����?';
		$this->xxbLang['ifsavenames']   = 'ȷ��Ҫ�����������?';
		$this->xxbLang['rediconfirm']   = 'ȷ��Ҫ���˹����ƶ��� < ' . $menu[10] . ' > ��?�鵵��˹���ֻ�ܱ�����,����һ�в�����������ֹ�� ';
 		$this->xxbLang['officesendfailed'] = '��Ǹ!�˹����޷����͵� < ' . $menu[6] . ' > ��';
 		$this->xxbLang['officedeletefailed']  = '��Ǹ!�˹��Ĳ�����,�޷�ɾ����';
 		$this->xxbLang['officedeleteeverfailed']  = '��Ǹ!�˹����޷�������ɾ����';
 		$this->xxbLang['officemodifyfailed']      = '��Ǹ!�޷��༭�˹��ģ�';
 		$this->xxbLang['officesetreceiverfailed'] = '��Ǹ!�޷����˹���ָ�� < ' . $this->xxbLang['receiver'] . ' > ��';
		$this->xxbLang['officesetfixfailed'] = '��Ǹ!�޷����˹����ƶ��� < ' . $menu[10] . ' > ��';
 		$this->xxbLang['setReceiOfficeFReadfailed'] = '��Ǹ!�޷������յ��Ĺ����ƶ��� < ' . $menu[10] . ' > ��';
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
		$this->xxbLang['help'] = '���ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net  
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э�����ɹ����������ͺ�������������
��ַ���»�����һ��������¥��¥����315��/�绰��0471-4606572/�����ʼ���brgd@nm14.net
��Ϣ�������칫������ - ��Ϣ����Э��';
	}
}
?>