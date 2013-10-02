<?php

                /***************************************************************************
		                                user.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: filesystem.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/
class fileSystem{
	private static $num = 0;
	
	public static function makeObj(){
		if(fileSystem::$num == 0){
			fileSystem::$num++;
			return new  fileSystem();
		}else{
			exit('Just can make only one fileSystem pbject!');
		}
	}
	
	private function fileSystem(){
		
	}	
}

?>