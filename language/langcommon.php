<?php

                /***************************************************************************
		                                commonfunction.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: langcommon.php,v 1.3 2006/02/08 01:45:25 brgd Exp $
		
		 ***************************************************************************/
abstract class langcommon{
	private static $num = Array();
	private    $tmenus = array();
	private    $usertype;
	private    $menunum = 3;
	protected  $menuval;

	protected  $globalConfig;
	
	protected function langcommon($obj){
		$this->setGlobalConfig($obj);
		
		$this->xxbLang['classlibg'] = "class=\"libg\"";
		$this->xxbLang['menulibg']  = "class=\"menulibg\"";
	}
	
	protected static function state($obname){
		$snum = langcommon::$num;
		$arraynum = count($snum);
		
		for($i = 0; $i < $arraynum; $i++){
			if($snum[$i] == $obname){
				return false;
			}
		}
		$snum[$arraynum] = $obname;
		langcommon::$num = $snum;
		return true;
	}
	
	protected function setGlobalConfig($obj){
		$this->globalConfig = $obj;
	}

	protected function setMenus($menuval){
		$this->menuval = $menuval;
		
		if($this->globalConfig->getXxbUser()->getUserinfo('userid') == 0){
			$this->usertype = $this->globalConfig->getUserType('guest');
		}else{
			$this->usertype = $this->globalConfig->getXxbUser()->getUserinfo('usertype');
		}
		
		switch ($this->usertype){
 			case $this->globalConfig->getUserType('guest'):
 				$this->setGuestMenu();
 				break;
 			case $this->globalConfig->getUserType('user'):
				$this->setUMenu();
				break;;
			case  $this->globalConfig->getUserType('admin'):
				$this->setAMenu();
				break;
 		}
 		
 		return $this->tmenus;
	}

 	private function setGuestMenu(){	
		$this->tmenus[0][0][0] = $this->menuval[0]; //首页[menu][submenu][para]
		$this->tmenus[0][0][1] = '1';
		$this->tmenus[0][0][2] = $this->globalConfig->getFileName('logintemp');//body file name,not include extension	
		
		$this->tmenus[1][0][0] = $this->menuval[1];//帮助
		$this->tmenus[1][0][1] = $this->menunum;
		$this->tmenus[1][0][2] = $this->globalConfig->getFileName('helptemp');
		
		$this->tmenus[2][0][0] = $this->menuval[2];//登录
		$this->tmenus[2][0][1] = '2';
		$this->tmenus[2][0][2] = $this->globalConfig->getFileName('logintemp');
		
		$this->setGuestSubMenu();	
	}
	
	private function setGuestSubMenu(){	
		/** 
		$this->tmenus[0][1][0]      = 'ff';
		$this->tmenus[0][1][1]      = '4';
		$this->ttmenus[0][1][2]      = 'hj';
		**/
	}
	
	private function setUMenu(){	
		$this->tmenus[0][0][0]      = $this->menuval[0]; //首页[menu][submenu][para]
		$this->tmenus[0][0][1]      = '1';
		$this->tmenus[0][0][2]      = $this->globalConfig->getFileName('hometemp');//body file name,not include extension	
		
		$this->tmenus[1][0][0]       = $this->menuval[4];//公文箱
		$this->tmenus[1][0][1]       = $this->menunum;
		$this->tmenus[1][0][2]       = $this->globalConfig->getFileName('viewoffice');
		
		$this->tmenus[2][0][0]      = $this->menuval[1];//帮助
		$this->tmenus[2][0][1]      = ++$this->menunum;
		$this->tmenus[2][0][2]      = $this->globalConfig->getFileName('helptemp');
		
		$this->tmenus[3][0][0]       = $this->menuval[3];//退出
		$this->tmenus[3][0][1]       = '2';
		$this->tmenus[3][0][2]       = $this->globalConfig->getFileName('logintemp');
		
		$this->setUSubMenu();	
	}
	
	private function  setUSubMenu(){
		if($this->globalConfig->getXxbUser()->getUserinfo('publish') == 'Y'){
			$this->tmenus[1][1][0]       = $this->menuval[5];//收文箱
			$this->tmenus[1][1][1]       = ++$this->menunum;
			$this->tmenus[1][1][2]       = $this->globalConfig->getFileName('receivedoffice');
			
			$this->tmenus[1][2][0]       = $this->menuval[6];//发文箱
			$this->tmenus[1][2][1]       = ++$this->menunum;
			$this->tmenus[1][2][2]       = $this->globalConfig->getFileName('sendedoffice');
			
			$this->tmenus[1][3][0]       = $this->menuval[10];//档案箱
			$this->tmenus[1][3][1]       = ++$this->menunum;
			$this->tmenus[1][3][2]       = $this->globalConfig->getFileName('officerecords');
			
			$this->tmenus[1][4][0]       = $this->menuval[7];//垃圾箱
			$this->tmenus[1][4][1]       = ++$this->menunum;
			$this->tmenus[1][4][2]       = $this->globalConfig->getFileName('viewtrash');
		
			$this->tmenus[2][1][0]       = $this->menuval[8];//常见问答
			$this->tmenus[2][1][1]       = ++$this->menunum;
			$this->tmenus[2][1][2]       = $this->globalConfig->getFileName('faqtemp');
			
			$this->tmenus[2][2][0]       = $this->menuval[9];//使用手册
			$this->tmenus[2][2][1]       = ++$this->menunum;
			$this->tmenus[2][2][2]       = $this->globalConfig->getFileName('manual');
		
		}
	}
	
	private function setAMenu(){
		$this->tmenus[0][0][0]      = $this->menuval[0]; //首页[menu][submenu][para]
		$this->tmenus[0][0][1]      = '1';
		$this->tmenus[0][0][2]      = $this->globalConfig->getFileName('hometemp');//body file name,not include extension	
		
		$this->tmenus[1][0][0]       = $this->menuval[4];//公文箱
		$this->tmenus[1][0][1]       ='3';
		$this->tmenus[1][0][2]       = $this->globalConfig->getFileName('filetemp');
				
		$this->tmenus[2][0][0]      = $this->menuval[1];//帮助
		$this->tmenus[2][0][1]      = ++$this->menunum;
		$this->tmenus[2][0][2]      = $this->globalConfig->getFileName('helptemp');
		
		$this->tmenus[3][0][0]       = $this->menuval[3];//退出
		$this->tmenus[3][0][1]       = '2';
		$this->tmenus[3][0][2]       = $this->globalConfig->getFileName('logintemp');
		
		$this->setASubMenu();
	}
	
	private function  setASubMenu(){
		
		$this->tmenus[0][1][0]      = 'ff';
		$this->tmenus[0][1][1]      = ++$this->menunum;
		$this->tmenus[0][1][2]      = 'hj';
		
	}
	
	public function getMenu($num1 = 0,$num2 = 0,$num3 = 0){
		return $this->menus[$num1][$num2][$num3];
	}
	
	public function getMenuCount(){
		return count($this->menus);
	}
	
	public function getSubMenuCount($num = 0){
		return count($this->menus[$num]);
	}
	
	public function getXxbLang($name){
		return $this->xxbLang[$name];
	}
}
?>