<?php
		/***************************************************************************
		                                user.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: user.php,v 1.5 2006/03/31 03:38:05 brgd Exp $
		
		 ***************************************************************************/
include_once("classes/user/alluser.php");

class User extends alluser{
	public static function makeObj($obj){
		if(self::state()){
			return new  User($obj);
		}else{
			exit('Just can make only one User object!');
		}
	}
	
	protected function User($obj){
		parent::__construct( $obj);
	}
	
	public function displayHead(){
		$template =  $this->globalConfig->getXxbTemplate();
		parent::displayHead($template);
	}
	
	public function displayBody(){
		$template =  $this->globalConfig->getXxbTemplate();
		$lang    = $this->globalConfig->getXxbLang();
		$body = $this->selectBody();
		
		switch($body){
			case $this->globalConfig->getFileName('hometemp'):
				$this->displayHome($template,$lang);
				break;
			case $this->globalConfig->getFileName('helptemp'):
				$this->displayHelp($template,$lang);
				break;
			case $this->globalConfig->getFileName('viewoffice'):
				$this->viewOffice($template,$lang);
				break;
			case $this->globalConfig->getFileName('receivedoffice'):
				$this->receivedoffice($template,$lang);
				break;
			case $this->globalConfig->getFileName('sendedoffice'):
				$this->sendedOffice($template,$lang);
				break;
			case $this->globalConfig->getFileName('officerecords'):
				$this->officerecords($template,$lang);
				break;
			case $this->globalConfig->getFileName('viewtrash'):
				$this->viewTrash($template,$lang);
				break;
			default:
				$this->displayHome($template,$lang);
				break;
		}
	}
	
	private function displayHome($template,$lang){
		
	}
	
	private function viewOffice($template,$lang){
		$do = $this->globalConfig->getGet('do');
		if(($do == null) || ($do == 1) || ($do == 6) || ($do == 7)){
			$this->includeOfficeFile('viewoffice');
			$office = viewoffice::makeObj($this->globalConfig);
		
			switch($do){
				case null:
					$office->displayAllOfficeFile($template,$lang);
					break;
				case 1:
					$office->displayOfficeType($template,$lang);
					break;
				case 6:
					$office->deleteAFile($template,$lang);
					break;
				case 7:
					$office->sendAFile($template,$lang);
					break;
				default:
					$office->displayAllOfficeFile($template,$lang);
					break;
			}
		}else{
			$office = $this->getOfficeObject();
			
			switch($this->globalConfig->getGet('do')){
				case 2:
					if($office->ifCanDisplayAFile()){
						$office->displayAFile($template,$lang);
					}else{
						$ok = $lang->getXxbLang('nofile');
						$office->displayCreateResult($template, $ok);
					}
					break;
				case 3:
					$office->displayANewFile($template,$lang);
					break;
				case 4:
					if($this->ifCanGoOn() == false){
						$this->canNotGoOn($office,$template,$lang);
						break;
					}else{
					$office->checkANewFile($template,$lang);
					break;}
				case 5:
					if($this->ifCanGoOn() == false){
						$this->canNotGoOn($office,$template,$lang);
						break;
					}
					$office->createANewFile($template,$lang);
					break;
				default:
					$this->includeFile('viewoffice');
					$office = viewoffice::makeObj($this->globalConfig);
					$office->displayAllOfficeFile($template,$lang);
					break;
			}
		}
		$template->show();
	}
	
	private function viewTrash($template,$lang){
		$this->includeOfficeFile('viewtrash');
		$office = viewtrash::makeObj($this->globalConfig);
		
		switch($this->globalConfig->getGet('do')){
			case null:
				$office->displayAllOfficeTrash($template,$lang);
				break;
			case 1:
				$office->displayADeletedOfficeFile($template,$lang);
				break;
			case 2:
				$office->undoADeletedOfficeFile($template,$lang);
				break;
			case 3:
				$office->delAOfficeFileEver($template,$lang);
				break;
			default:
				$office->displayAllOfficeTrash($template,$lang);
				break;
		}
		$template->show();
	}

	private function receivedoffice($template,$lang){
		$this->includeOfficeFile('receivedoffice');
		$office = receivedoffice::makeObj($this->globalConfig);
		
		switch($this->globalConfig->getGet('do')){
			case null:
				$office->displayAllReceivedOffice($template,$lang);
				break;
			case 1:
				$office->displayAReceivedOffice($template,$lang);
				break;
			default:
				$office->displayAllReceivedOffice($template,$lang);
				break;
		}
		$template->show();
	}

	private function sendedOffice($template,$lang){
		$this->includeOfficeFile('sendedoffice');
		$office = sendedoffice::makeObj($this->globalConfig);
		
		switch($this->globalConfig->getGet('do')){
			case null:
				$office->displayAllSendedOffice($template,$lang);
				break;
			case 1:
				$office->displayASendedOfficeFile($template,$lang);
				break;
			case 2:
				$office->undoASendedOfficeFile($template,$lang);
				break;
			case 3:
				$office->setASendedOfficeFileReceiver($template,$lang);
				break;
			case 4:
				$office->insertReceiverInDB($template,$lang);
				break;
			case 5:
				$office->setFix($template,$lang);
				break;
			default:
				$office->displayAllSendedOffice($template,$lang);
				break;
		}
		$template->show();
	}

	private function officerecords($template,$lang){
		$this->includeOfficeFile('officerecords');
		$office = officerecords::makeObj($this->globalConfig);
		
		switch($this->globalConfig->getGet('do')){
			case null:
				$office->displayAllOfficeRecords($template,$lang);
				break;
			case 1:
				$office->displayAOfficeRecords($template,$lang);
				break;
			default:
				$office->displayAllOfficeRecords($template,$lang);
				break;
		}
		$template->show();
	}

	private function ifCanGoOn(){
		if(count($this->globalConfig->getAllPost()) != 0){
			return true;
		}else{
			return false;
		}
	}

	private function canNotGoOn($office,$template,$lang){
		$tempname = $this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('officecreated');
		$template->loadTemplateFile($tempname . $this->globalConfig->getGlobal('htmlfileext'));
		
		$oback = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "\" id=\"officeback\">" . $lang->getXxbLang('back') . "</a>";
		$ok = $lang->getXxbLang('officeillegal');
		$office->displayCreateResult($template, $oback, $ok);
	}
}
?>