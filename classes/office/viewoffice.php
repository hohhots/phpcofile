<?php
		/***************************************************************************
		                                viewoffice.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: viewoffice.php,v 1.5 2006/03/31 03:38:05 brgd Exp $
		
		 ***************************************************************************/
//display all office files ceated by youself
//and all officetype that can ceated

include_once("classes/office/office.php");

class viewoffice extends office{
	private function viewoffice($obj){ //Constructor
		parent::__construct( $obj);
	}
	
	public static function makeObj($obj){
		if(self::state('viewoffice')){
			return new  viewoffice($obj);
		}else{
			exit('Just can make only one viewoffice object!');
		}
	}
	
	public function displayOfficeType($template,$lang){
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('viewoffice') . $this->globalConfig->getGlobal('htmlfileext'));
			
		$officetype = $this->getOfficeTypeInfo();
		
		$template->setCurrentBlock("block1");
			$template->setVariable(array(
					"select"           => $lang->getXxbLang('selectofficetype'),
					"idname"           => $lang->getXxbLang('idorder'),
					"namename"         => $lang->getXxbLang('name'),
					"descriptionname"  => $lang->getXxbLang('description')
			));
 			
 			$template->setCurrentBlock("block1tr");
 			$j = 0;
			for($i = 0; $i < count($officetype); $i++){
				if(($j++)%2 == 0){$trbgcolor = 'otrbgcolor1';}else{$trbgcolor = 'otrbgcolor2';}
				$template->setVariable(array(
					"trbgcolor"        => $trbgcolor,
					"id"               => $officetype[$i]['officetypeid'],
					"name"          => '<a href="?target=' . $this->globalConfig->getGet('target') . '&do=3&type=' . $officetype[$i]['officetypeid'] . '"><div class="href">'  . $officetype[$i]['name'] . '</div></a>',
					"description"  => $officetype[$i]['description']
				));
				$template->parseCurrentBlock("block1tr");
			}
		$template->parseCurrentBlock("block1");	
	}

	public function deleteAFile($template,$lang){
		if($this->setDelMark()){
			$this->displayAllOfficeFile($template,$lang);
		}else{
			$ok = $lang->getXxbLang('officedeletefailed');
			$this->displayCreateResult($template, $ok);
		}
	}
	
	public function sendAFile($template,$lang){
		if($this->setSendMark()){
			$this->displayAllOfficeFile($template,$lang);
		}else{
			$ok = $lang->getXxbLang('officesendfailed');
			$this->displayCreateResult($template, $ok);
		}
	}
	
	private function setSendMark(){
		$type = $this->globalConfig->getGet('type');
		$id = $this->globalConfig->getGet('id');
		
		$temp = $this->ifIsTheOwner($type,$id);
		if(!$temp){
			return false;
		}
		if($this->alreadyDeleted($type,$id)){
			return false;
		}
		if($this->alreadySend($type,$id)){
			return false;
		}
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET send = " . time() . " WHERE fileid = " . $id .
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 628");
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	private function setDelMark(){
		$type = $this->globalConfig->getGet('type');
		$id = $this->globalConfig->getGet('id');
		
		if(!($this->ifIsTheOwner($type,$id))){
			return false;
		}
		if($this->alreadyDeleted($type,$id)){
			return false;
		}
		if($this->alreadySend($type,$id)){
			return false;
		}
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET deltime = '" . time() . 
				"' WHERE fileid = " . $id .
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 600");
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}
			
}
?>