<?php
  
                /***************************************************************************
		                                index.php
		                             -------------------
		    begin                : 2005 June 25 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: index.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/
$index = new allBegin();

class allBegin{
	
	private $globalConfig;// = new globalConfig();
	
	public function  allBegin(){
	
		error_reporting  (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
		set_magic_quotes_runtime(0); // Disable magic_quotes_runtime
		
		$configphp = "config.php";
	
		if(file_exists($configphp)){
				require_once($configphp);
		}else{
			exit('<font color="#ff0000" size="6">Can\'t find the file ' . $configphp . '!</font>');
		}
		
		$this->globalConfig = globalConfig::makeObj();
		
		$this->display();
	}
	
	private function  display(){
		$user = $this->globalConfig->getXxbUser();
		
		$user->displayHead();
		$user->displayBody();
		$user->displayFoot();
	}
}
?>
