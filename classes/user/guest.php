<?php

                /***************************************************************************
		                                guest.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: guest.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/
include_once('classes/user/alluser.php');

class Guest extends alluser{
	
	public static function makeObj($obj){
		if(self::state()){
			return new  Guest($obj);
		}else{
			exit('Just can make only one User pbject!');
		}
	}
	
	protected function Guest($obj){
		parent::__construct( $obj);
	}
	
	public function displayHead(){
		$template =  $this->globalConfig->getXxbTemplate();
		parent::displayHead($template);
	}
	
	public function displayBody(){
		
		$body = $this->selectBody();
		
		switch($body){
			case $this->globalConfig->getFileName('logintemp'):
				$this->displayLogin();
				break;
			case $this->globalConfig->getFileName('helptemp'):
				$this->displayHelp();
				break;
		}
	}
	
	private function displayLogin(){
		
		$lang    = $this->globalConfig->getXxbLang();
		$template =  $this->globalConfig->getXxbTemplate();
		
		if($this->globalConfig->getGet('fail') == 1){
			$fail = $lang->getXxbLang('userfail');
		}
		
		$template->loadTemplateFile($this->globalConfig->getFileName('logintemp') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("login1");
 			$template->setVariable(array(
 				"fail"   => $fail,
				"id"     => $lang->getXxbLang('userid'),
				"pass"   => $lang->getXxbLang('userpass'),
				"login"  => '<a href="#" onclick="allsubmit(loginform)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('userlogin') . '</div></div></a>',
				"reset"  => '<a href="#" onclick="allreset(loginform)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('userreset') . '</div></div></a>',
			));
 		$template->parseCurrentBlock("login1"); 
 		$template->show();	
	}
}
?>