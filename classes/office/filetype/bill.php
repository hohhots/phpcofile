<?php
		/***************************************************************************
		                                bill.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id$
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class bill extends office{
	private function bill($obj){ //Constructor
		parent::__construct($obj);
	}
	
	public static function makeObj($obj){
		if(self::state()){
			return new  bill($obj);
		}else{
			exit('Just can make only one bill object!');
		}
	}
}
?>