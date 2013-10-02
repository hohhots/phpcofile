<?php
		/***************************************************************************
		                               officerecords.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: officerecords.php,v 1.4 2006/03/29 09:29:41 brgd Exp $
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class officerecords extends office{
	private function officerecords($obj){ //Constructor
		parent::__construct( $obj);
	}
	
	public static function makeObj($obj){
		if(self::state('officerecords')){
			return new  officerecords($obj);
		}else{
			exit('Just can make only one officerecords object!');
		}
	}
	
	public function displayAOfficeRecords($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if(!$this->ifIsTheReceiverAndRead($type,$id) && 
			(!$this->ifIsTheOwner($type,$id) && 
					!$this->getFileFreezeState($type,$id))){
			$ok = $lang->getXxbLang('nofile');
			$this->displayCreateResult($template, $ok);
		}else{
			$officetype = $this->globalConfig->getXxbUser()->getOfficeObject();
			$officetype->displayAFile($template,$lang);
		}
	}

	public function displayAllOfficeRecords($template,$lang){
		$ftemp = $this->getOfficeRecordsFile($lang);
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('officerecords') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("block0");
		$template->setVariable(array(
			"typetitle"    => $lang->getXxbLang('officerecords'),
			"ltitle"       => $lang->getXxbLang('title'),
			"lpublishtime" => $lang->getXxbLang('pubtime'),
			"lbureauname"  => $lang->getXxbLang('pubbureau'),
			"lofficetype"  => $lang->getXxbLang('selecttype'),
		));
		if(count($ftemp) == 0){	
			$template->setCurrentBlock("block02");
				$template->setVariable(array(
					"noofficefile"  => $lang->getXxbLang('noofficerecordsfile'),
				));
			$template->parseCurrentBlock("block02");
		}else{
			$template->setCurrentBlock("block01");
			
			for($z = 0; $z < count($ftemp); $z++){
				$j = 0;
				if(($j++)%2 == 0){$trbgcolor = 'otrbgcolor1';}else{$trbgcolor = 'otrbgcolor2';}
				$template->setVariable(array(
					"trbgcolor"   => $trbgcolor,
					"title"       => $ftemp[$z]['title'],
					"publishtime" => date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'), $ftemp[$z]['pubtime']),
					"bureauname"  => $ftemp[$z]['bureauname'],
					"officetype"  => $ftemp[$z]['officetypename'],
				));
				$template->parseCurrentBlock("block01");
			}
		}
		
		$template->parseCurrentBlock("block0");
	}

	private function getOfficeRecordsFile($lang){
		$display  = 1;
		
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$sql = "SELECT officetypeid, fileid, firstread FROM "  . $this->globalConfig->getSqlTable('sendtobureau') . 
				" WHERE bureauid = " . $userinfo['bureauid'] . 
				" GROUP BY fileid DESC";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> officerecords.php 90");
		}
		$k = 0;
		while($row = $result->fetchRow()){
			if((($row['firstread'] != 0) && (($row['firstread'] + $this->lasttime) < $this->globalConfig->getSystemTime())) ||
				($this->ifIsTheOwner($row['officetypeid'],$row['fileid']) && 
					$this->getFileFreezeState($row['officetypeid'],$row['fileid']))){
				$records[$k]['officetypeid'] = $row['officetypeid']; //set officetypeid
				$records[$k]['fileid'] = $row['fileid']; //set fileid
				
					$temp = $this->getAOfficeTypeName($row['officetypeid']);    //get office type name
				$records[$k]['officetypename'] = $temp;                      //set office type name
					$temp = $this->getOfficeTypeTableName($row['officetypeid']); //get file table name.
					$temp = $this->getAOfficeFile($temp, $row['fileid']);       //get the file information
				$records[$k]['title']   = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $row['officetypeid'] . "&id=" . $row['fileid'] . "&do=$display\"><div class=\"lhref\">" . $temp['title'] . "</div></a>";
				$records[$k]['pubtime'] = $temp['pubtime'];                 //set pubtime
				$records[$k]['bureauname'] = $this->getTheBureauName($temp['userid']); //set bureauname

				$k++;
			}
		}
		return $records;
	}	


}
?>