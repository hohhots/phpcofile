<?php

                /***************************************************************************
		                                alluser.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: alluser.php,v 1.3 2006/03/24 02:37:13 brgd Exp $
		
		 ***************************************************************************/
abstract class alluser{
	private static $num = 0;
	
	protected $globalConfig;
	
	protected $heads = array();
	protected $cdate;
	protected $menu = array();	
	
	protected $userinfo = array(); //sessionid,id,name,usertype,ip,env,
	
	protected function alluser($obj){
		$this->setGlobalConfig($obj);
		$this->userinfo = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
	}
	
	protected static function state(){
		if(alluser::$num == 0){
			alluser::$num++;
			return true;
		}else{
			return false;
		}
	}
	
	public function getUserinfo($val){
		return $this->userinfo[$val];
	}

	public function getOfficeObject(){
		$type = $this->globalConfig->getGet('type');
		switch($type){
			case null:
				$this->includeOfficeFile('viewoffice');
				$office = viewoffice::makeObj($this->globalConfig);
				break;
			case 1:
				$this->includeOfficeFile('command');
				$office = command::makeObj($this->globalConfig);
				break;
			case 2:
				$this->includeOfficeFile('decision');
				$office = decision::makeObj($this->globalConfig);
				break;
			case 3:
				$this->includeOfficeFile('bulletin');
				$office = bulletin::makeObj($this->globalConfig);
				break;
			case 4:
				$this->includeOfficeFile('encyclic');
				$office = encyclic::makeObj($this->globalConfig);
				break;
			case 5:
				$this->includeOfficeFile('notice');
				$office = notice::makeObj($this->globalConfig);
				break;
			case 6:
				$this->includeOfficeFile('aviso');
				$office = aviso::makeObj($this->globalConfig);
				break;
			case 7:
				$this->includeOfficeFile('bill');
				$office = bill::makeObj($this->globalConfig);
				break;
			case 8:
				$this->includeOfficeFile('reporting');
				$office = reporting::makeObj($this->globalConfig);
				break;
			case 9:
				$this->includeOfficeFile('instructions');
				$office = instructions::makeObj($this->globalConfig);
				break;
			case 10:
				$this->includeOfficeFile('reversion');
				$office = reversion::makeObj($this->globalConfig);
				break;
			case 11:
				$this->includeOfficeFile('opinion');
				$office = opinion::makeObj($this->globalConfig);
				break;
			case 12:
				$this->includeOfficeFile('cases');
				$office = cases::makeObj($this->globalConfig);
				break;
			case 13:
				$this->includeOfficeFile('summary');
				$office = summary::makeObj($this->globalConfig);
				break;
			case 14:
				$this->includeOfficeFile('others');
				$office = others::makeObj($this->globalConfig);
				break;
			default:
				$this->includeOfficeFile('viewoffice');
				$office = viewoffice::makeObj($this->globalConfig);
				break;
		}
		return $office;
	}

	protected function includeOfficeFile($fname){
		$a = $this->globalConfig->getDirName('class') . $this->globalConfig->getDirName('office') . 
					$this->globalConfig->getFileName($fname) . $this->globalConfig->getGlobal('phpfileext'); 
		$b = $this->globalConfig->getDirName('class') . $this->globalConfig->getDirName('officefilestype') . 
					$this->globalConfig->getFileName($fname) . $this->globalConfig->getGlobal('phpfileext');
		if(file_exists($a)){
			require_once($a);
			return;
		}
		if(file_exists($b)){
			require_once($b);
			return;
		}
		echo("sorry!This file (" . $fname . ") does not exist!  class/user/alluser.php");
		return;
	}

	protected function displayHead($template){
		$target = $this->globalConfig->getGet('target');
		$lang    = $this->globalConfig->getXxbLang();
		
		$this->setHeadInfo();
		
		$this->setHeads1();
		include_once("classes/office/office.php");
		$template->loadTemplateFile($this->globalConfig->getFileName('headtemp') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("head1");
 			$template->setVariable(array(
 				"searchscript" => $lang->getXxbLang('resetscript') . $lang->getXxbLang('checkscript'),
				"username" => $lang->getXxbLang('currentuser') . $this->userinfo['name'] . ' <' . $this->userinfo['bname'] . '>',
				"doctitle" => $this->heads['doctitle'],
				"date"     => $this->cdate,
			));
 		 
 			$template->setCurrentBlock("head1col1");
 			for($i = 0; $i < count($this->menu[0]); $i++){
				if($target == $lang->getMenu($i,0,1)){
					$this->menu[0][$i][0] = $lang->getXxbLang('classlibg');

				}else{
					$z = $lang->getSubMenuCount($i);
					if($z > 1){
						for($j = 1; $j < $z ; $j++){
							if($target == $lang->getMenu($i,$j,1)){
								$this->menu[0][$i][0] = $lang->getXxbLang('classlibg');
							}
						}
					}
				}
				$template->setVariable(array(
					"menuclass" => $this->menu[0][$i][0],
					"menu"      => $this->menu[0][$i][1],
				));
				$template->parseCurrentBlock("head1col1");
			}
		$template->parseCurrentBlock("head1"); 
		$template->setCurrentBlock("head2");
			$tname = $this->displaySubMenu();

			--$tname;
			$tnum = $lang->getSubMenuCount($tname) - 1;
			if($tnum > 0){
				$this->setHeads2($tname,$tnum);
				
				$template->setVariable(array(
					"searchac" => '#',
 					"search" => $lang->getXxbLang('search'),
 				));
				
				$template->setCurrentBlock("head2col1"); 
				for($i = 0; $i < $tnum; $i++)	{
					$template->setVariable(array(
						"submenuclass" => $this->menu[1][$i][0],
						"submenu"      => $this->menu[1][$i][1],
					));
					$template->parseCurrentBlock("head2col1");
				}
			}
			$template->parseCurrentBlock("head2"); 
		$template->show();
	}
	
	protected function setHeads1(){
		$target = $this->globalConfig->getGet('target');
		$do      = $this->globalConfig->getGet('do');
		$lang    = $this->globalConfig->getXxbLang();
		$this->menu = null;
	
		$this->cdate = $this->globalConfig->getGlobal('cNumDate')
			. '&nbsp;' . date("G") . ':' . date("i") . '&nbsp;' . $lang->getXxbLang(date("D"));
		
		$this->heads['doctitle'] = $lang->getXxbLang('doctitle');
		
		for($i = 0; $i < ($lang->getMenuCount()); $i++){
			if(($target == ($lang->getMenu($i,0,1))) && ($do == null)){
				$this->menu[0][$i][0]  = $lang->getXxbLang('classlibg');
				$this->menu[0][$i][1]  = $lang->getMenu($i,0,0);
			}else{
				$this->menu[0][$i][0]  = '';
				$this->menu[0][$i][1]  = '<a href="?target=' . $lang->getMenu($i,0,1) . '">' . $lang->getMenu($i,0,0) . '</a>';
			}
		}
	}
	
	protected function displaySubMenu(){  //Get the number of menu 
									//that will display submenu
		$target = $this->globalConfig->getGet('target');
		$lang    = $this->globalConfig->getXxbLang();
		
		$z = $lang->getMenuCount();
		for($i = 0; $i < $z ; $i++){
			$y = $lang->getSubMenuCount($i);
			for($j = 0; $j < $y ; $j++){	
				if($target == $lang->getMenu($i,$j,1)){
					$tname = $i + 1;
				}
			}
		}
		return $tname;
	}
	
	protected function setHeads2($name,$tnum){
		$target = $this->globalConfig->getGet('target');
		$lang    = $this->globalConfig->getXxbLang();
		$do      = $this->globalConfig->getGet('do');
		$this->menu = null;
		
		for($i = 0; $i < $tnum; $i++){
			$this->menu[1][$i][1]  =  '<a href="?target=' . $lang->getMenu($name,($i+1),1) . '">' . $lang->getMenu($name,($i+1),0). '</a>';
			if($target == $lang->getMenu($name,($i+1),1)){
				$this->menu[1][$i][0]  =  $lang->getXxbLang('classlibg');
				if($do == null){
					$this->menu[1][$i][1]  =  $lang->getMenu($name,($i+1),0);
				}
				
			}else{
				$this->menu[1][$i][0]  =  '';
			}
		}
	}
	
	protected function setHeadInfo(){
		header('Content-Type: text/html; charset=gb2312');
	}
	
	protected function selectBody(){
		$target = $this->globalConfig->getGet('target');
		$lang    = $this->globalConfig->getXxbLang();
		
		$z = $lang->getMenuCount();
		if($z > 1){
			for($i = 0; $i < $z ; $i++){
				$y = $lang->getSubMenuCount($i);
				for($j = 0; $j < $y ; $j++){	
					if($target == $lang->getMenu($i,$j,1)){
						return $lang->getMenu($i,$j,2);
					}
				}
			}
		}
	}
	
	protected function displayHelp(){
		$lang    = $this->globalConfig->getXxbLang();
		$template =  $this->globalConfig->getXxbTemplate();
		
		$template->loadTemplateFile($this->globalConfig->getFileName('helptemp') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("help1");
			$template->setVariable(array(
 				"help"      =>  $lang->getXxbLang('help')
			));
 		$template->parseCurrentBlock("help1"); 
 		$template->show();
	}
	
	public function displayFoot(){
		$lang    = $this->globalConfig->getXxbLang();
		
		$template =  $this->globalConfig->getXxbTemplate();
		
		$template->loadTemplateFile($this->globalConfig->getFileName('foottemp') . $this->globalConfig->getGlobal('htmlfileext'));
			
		$template->setCurrentBlock("foot1");
 			$template->setVariable(array(
 				"f1"  => $lang->getXxbLang('foot1'),
				"f2"  => $lang->getXxbLang('foot2'),
				"f3"  => $lang->getXxbLang('foot3')
			));
 		$template->parseCurrentBlock("foot1"); 
 		$template->show();
	}
	
	private function setGlobalConfig($obj){
		$this->globalConfig = $obj;
	}
}
?>