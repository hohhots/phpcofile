<?php

                /***************************************************************************
		                                english.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: english.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/	 
class xxbLanguage{
	private$globalConfig;
	private static $num = 0;
	private $xxbLang = array();
	
	public static function makeObj($obj){
		if(xxbLanguage::$num == 0){
			xxbLanguage::$num++;
			return new  xxbLanguage($obj);
		}else{
			exit('Just can make only one xxbLanguage pbject!');
		}
	}
	
	private function xxbLanguage($obj){
		$this->globalConfig = $obj;
		
		$this->xxbLang['alltitle'] = 'huhhot government';
	}
	
	public function getXxbLang($name){
		return $this->xxbLang[$name];
	}
}
?>