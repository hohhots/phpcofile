<?php
		/***************************************************************************
		                                reporting.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id$
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class reporting extends office{
	private function reporting($obj){ //Constructor
		parent::__construct($obj);
	}
	
	public static function makeObj($obj){
		if(self::state()){
			return new reporting($obj);
		}else{
			exit('Just can make only one reporting object!');
		}
	}
}
?>