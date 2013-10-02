<?php
		/***************************************************************************
		                                user.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: admin.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/
include_once("classes/user/alluser.php");

class Admin extends alluser{
	public static function makeObj($obj){
		if(self::state()){
			return new  Admin($obj);
		}else{
			exit('Just can make only one User object!');
		}
	}
	
	protected function Admin($obj){
		parent::__construct( $obj);
	}
	
	public function displayHead(){
		$template =  $this->globalConfig->getXxbTemplate();
		parent::displayHead($template);
		
		$lang    = $this->globalConfig->getXxbLang();
		
		$template->setCurrentBlock("head4"); 
 			$template->setVariable(array(
				"username"       => $lang->getXxbLang('currentuser') .  $this->userinfo['organization'] . '&nbsp;-&nbsp;' . $this->userinfo['administrator_name'],
			));
		$template->parseCurrentBlock("head4");
		
		$template->show();
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
}
?>