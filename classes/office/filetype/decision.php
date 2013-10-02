<?php
		/***************************************************************************
		                                decision.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id$
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class decision extends office{
	private function decision($obj){ //Constructor
		parent::__construct($obj);
	}
	
	public static function makeObj($obj){
		if(self::state()){
			return new  decision($obj);
		}else{
			exit('Just can make only one decision object!');
		}
	}
}
?>