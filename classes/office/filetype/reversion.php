<?php
		/***************************************************************************
		                                reversion.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id$
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class reversion extends office{
	private function reversion($obj){ //Constructor
		parent::__construct($obj);
	}
	
	public static function makeObj($obj){
		if(self::state()){
			return new reversion($obj);
		}else{
			exit('Just can make only one reversion object!');
		}
	}
}
?>