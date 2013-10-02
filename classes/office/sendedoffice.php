<?php
		/***************************************************************************
		                                sendedoffice.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: sendedoffice.php,v 1.6 2006/03/29 08:23:14 brgd Exp $
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class sendedoffice extends office{
	private function sendedoffice($obj){ //Constructor
		parent::__construct( $obj);
	}
	
	public static function makeObj($obj){
		if(self::state('sendedoffice')){
			return new  sendedoffice($obj);
		}else{
			exit('Just can make only one sendedoffice object!');
		}
	}
	
	public function displayASendedOfficeFile($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if(!$this->ifIsTheOwner($type,$id) || 
			!$this->alreadySend($type,$id) || 
			$this->getFileFreezeState($type,$id)){
			$ok = $lang->getXxbLang('nofile');
			$this->displayCreateResult($template, $ok);
		}else{
			$officetype = $this->globalConfig->getXxbUser()->getOfficeObject();
			$officetype->displayAFile($template,$lang);
		}
	}

	public function displayAllSendedOffice($template,$lang){
		$ftemp = $this->getOfficeSendedFile($lang);
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('sendedoffice') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("block0");
		$template->setVariable(array(
			"confirmscript"   => $lang->getXxbLang('confirmscript'),
			"typetitle"       => $lang->getXxbLang('sendedoffice'),
			"lurgencydegree"  => $lang->getXxbLang('lurgencydegree'),
			"lofficetype"     => $lang->getXxbLang('selecttype'),
			"ltitle"          => $lang->getXxbLang('title'),
			"lsentime"        => $lang->getXxbLang('sentime'),
			"ledit"           => $lang->getXxbLang('edit'),
		));
		if(count($ftemp) == 0){	
			$template->setCurrentBlock("block02");
				$template->setVariable(array(
					"noofficefile"  => $lang->getXxbLang('nosendedofficefile'),
				));
			$template->parseCurrentBlock("block02");
		}else{
			$template->setCurrentBlock("block01");
			$j = 0;
			for($z = 0; $z < count($ftemp); $z++){
				if(($j++)%2 == 0){$trbgcolor = 'otrbgcolor1';}else{$trbgcolor = 'otrbgcolor2';}
				$template->setVariable(array(
					"trbgcolor"     => $trbgcolor,
					"urgencydegree" => $ftemp[$z]['lurgencydegree'],
					"officetype"    => $ftemp[$z]['lofficetypename'],
					"title"         => $ftemp[$z]['ltitle'],
					"sentime"       => date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'), $ftemp[$z]['lsentime']),
					"undoid"        => $ftemp[$z]['undoid'],
					"undo"          => $ftemp[$z]['lundo'],
					"receiver"      => $ftemp[$z]['lreceiver'],
					"send"           => $ftemp[$z]['lsend'],
				));
				$template->parseCurrentBlock("block01");
			}
		}
		$template->parseCurrentBlock("block0");	
	}

	public function setASendedOfficeFileReceiver($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');

		if(!($this->ifIsTheOwner($type,$id)) || 
				($this->alreadyDeleted($type,$id)) || 
				!($this->alreadySend($type,$id)) || 
				($this->getFileFreezeState($type,$id))){
			$ok = $lang->getXxbLang('officesetreceiverfailed');
			$this->displayCreateResult($template, $ok);
			return;
		}
		
		$bureauname   = $this->getBureauName($type, $id);
		
		$afile = $this->getAOfficeFile($this->getOfficeTypeTableName($type),$id);
		
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('setoreceiver') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("block0");
		$template->setVariable(array(
			"ifsavenames" => $lang->getXxbLang('ifsavenames'),
			"title"     => $lang->getXxbLang('receivertitle'),
			"filetitle" => $afile['title'],
			"action"    => '?target=' . $this->globalConfig->getGet('target') .
							'&type=' . $this->globalConfig->getGet('type') .
							'&id=' . $this->globalConfig->getGet('id') .
							'&do=' . ($this->globalConfig->getGet('do') + 1),
			"unreceivername"  => $lang->getXxbLang('unreceivername'),
			"receivername"=> $lang->getXxbLang('receivername'),
			"delete"   => $lang->getXxbLang('delete') . ' ',
			"add"      =>  $lang->getXxbLang('add') . ' ',
			"addall"   => $lang->getXxbLang('addall'),
			"delall"   => $lang->getXxbLang('delall'),
			"ok"       =>  $lang->getXxbLang('ok') ,
			"reset"    => $lang->getXxbLang('resetreceiver'),
			
		));
		
		$template->setCurrentBlock("block01");
		for($i = 0; $i < count($bureauname[0]); $i++){
			$template->setVariable(array(
				"ureceiver" => '<option value="' . $bureauname[0][$i]['bureauid'] . '">' . $bureauname[0][$i]['name'] . '</option>',
			));
			$template->parseCurrentBlock("block01");
		}
		$template->setCurrentBlock("block02");
		for($i = 0; $i < count($bureauname[1]); $i++){
			$template->setVariable(array(
				"receiver" => '<option value="' . $bureauname[1][$i]['bureauid'] . '">' . $bureauname[1][$i]['name'] . '</option>',
			));
			$template->parseCurrentBlock("block02");
		}

		$template->parseCurrentBlock("block0");	
		
	}

	public function insertReceiverInDB($template,$lang){
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		$sbname = $this->globalConfig->getPost('sbname');

		$this->delBureauFromSendTo();
		if($sbname != null){
			if(!$this->correctString($sbname)){
				$ok = $lang->getXxbLang('officesetreceiverfailed');
				$this->displayCreateResult($template, $ok);
				return;
			}
		
			$namesarray = array_unique(explode(",", $sbname));
		
			$sql = "SELECT * FROM " . $this->globalConfig->getSqlTable('bureau') . 
				" WHERE bureauid <> " . $userinfo['bureauid'];
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage());
			}
			$k = 0;
			while($row = $result->fetchRow()){
				$beauru[$k] = $row['bureauid'];
				$k++;
			}
		
			if(count(array_diff($namesarray, $beauru)) == 0){ //if namesarray has right id
				$this->inseretBureauToSendToBureau($namesarray);
			}
		}
		$this->displayAllSendedOffice($template,$lang);
	}

	public function setFix($template,$lang){
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		$state = true;
		
		if(!($this->ifIsTheOwner($type,$id)) || 
				($this->alreadyDeleted($type,$id))  || 
				($this->getFileFreezeState($type,$id))){
			$state = false;
		}
		/** get receive and unreceive ofile bureau name
		$bname = $this->getBureauName($type,$id);
		if(count($bname[1]) == 0){ //if receive ofile bureau name is empty
			$state = false;
		}
		**/
		if(!$state){
			$ok = $lang->getXxbLang('officesetfixfailed');
			$this->displayCreateResult($template, $ok);
			return;
		}
		$names[0] = $userinfo['bureauid'];
		$this->inseretBureauToSendToBureau($names); //default send to own bureau
		
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET freezed = " . $this->globalConfig->getSystemTime() . " WHERE fileid = " . $id .
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
		
		$this->displayAllSendedOffice($template,$lang);
	}

	public function undoASendedOfficeFile($template,$lang){
		if($this->setUnSendMark()){
			$this->displayAllSendedOffice($template,$lang);
		}else{
			$ok = $lang->getXxbLang('officeunsendfailed');
			$this->displayCreateResult($template, $ok);
		}
	}

	private function inseretBureauToSendToBureau($names){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();

		for($i = 0; $i < count($names); $i++){
			$sql = "INSERT INTO "  . $this->globalConfig->getSqlTable('sendtobureau') . 
				" (officetypeid, fileid, bureauid) VALUES ($type, $id, " . $names[$i] . ")";
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage());
			}
		}
	}

	private function delBureauFromSendTo(){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		$sql = "DELETE FROM " . $this->globalConfig->getSqlTable('sendtobureau') . 
				" WHERE officetypeid = " . $type . " AND fileid = " . $id;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
	}

	private function correctString($name){
		if(strlen($name) != 0){
			if(ereg("^[0-9]{1}", $name) && ereg("[0-9]{1}$", $name)){
				for($i = 0; $i < strlen($name); $i++){
					if(!ereg("[0-9\,]{1}", substr($name, $i, 1)) || 
						(substr($name, $i, 2) == ',,')){
						return false;
					}
				}
			}
		}
		return true;
	}

	private function getOfficeSendedFile($lang){
		$x = 0;
		
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$ot = $this->getOfficeTypeInfo();
		
		for($i = 0; $i < count($ot); $i++){
			$sql = "SELECT  of.officetypeid, f.fileid, f.urgencydegree, f.title, of.send FROM " . $this->globalConfig->getSqlTable('officefile') . " AS of, " . $ot[$i]['ictbname'] . " AS f " . 
				"WHERE  of.officetypeid = " . $ot[$i]['officetypeid'] . " AND f.fileid = of.fileid AND f.userid = " . $userinfo['userid'] . " AND of.freezed = '0' AND of.send <> '0' " .  
				" GROUP BY of.send DESC";
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage());
			}
			
			$tempnum = $result->numRows();
			
			if($tempnum != 0){
				$k = 0;
				$f = null;
				while($row = $result->fetchRow()){
					$f[$k]['fileid']        =  $row['fileid'];
					$f[$k]['urgencydegree'] =  $row['urgencydegree'];
					$f[$k]['title']         =  $row['title'];
					$f[$k]['send']          =  $row['send'];
					$k++;
				}
				
				for($j = 0; $j < count($f); $j++){
					$display   = 1;
					$undo      = 2;
					$receiver  = 3;
					$fixed     = 5;
					
					$tofile[$x]['lofficetypename'] = $ot[$i]['name'];
 					$tofile[$x]['ltitle']          = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$display\"><div class=\"lhref\">" . $f[$j]['title'] . "</div></a>";
 					$tofile[$x]['lsentime']        = $f[$j]['send'];
					
					$tofile[$x]['undoid'] = 'id="ahref"';
					$tofile[$x]['lundo']  = '<a href="?target=' . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$undo\"><div class=\"href\">" . $lang->getXxbLang('undo') . "</div></a>";
					if($this->alreadySetBureau($ot[$i]['officetypeid'],$f[$j]['fileid'])){
						$tofile[$x]['undoid'] = 'class="disable"';
						$tofile[$x]['lundo']  =  $lang->getXxbLang('undo');
					}
					
					$tofile[$x]['lsend']   = "<a href=\"#\" onclick=\"rediconfirm('" . $lang->getXxbLang('rediconfirm') . "','?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$fixed')\"" . "><div class=\"href\">" . $lang->getXxbLang('send') . "</div></a>";
					$tofile[$x]['lreceiver'] = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$receiver\"><div class=\"href\">" . $lang->getXxbLang('receiver') . "</div></a>";
					
					$temp = $lang->getXxbLang('urgencydegree');
					$tempm = '<div style="color:#f00">' . $f[$j]['urgencydegree'] . '</div>';
					
					if($f[$j]['urgencydegree'] == $temp[1] || 
						($f[$j]['urgencydegree'] == $temp[2])){
 						$tofile[$x]['lurgencydegree']  = $tempm;
 					}
 				
 					if(($f[$j]['urgencydegree'] == $temp[0]) ||
						($f[$j]['urgencydegree'] == '')){
 						$tofile[$x]['lurgencydegree']  = $temp[0];
 					}
					$x++;
				}
				$tempnum = 0;
			}
			
		}
		return $tofile;
	}

	private function setUnSendMark(){
		$type = $this->globalConfig->getGet('type');
		$id = $this->globalConfig->getGet('id');
		
		if(!($this->ifIsTheOwner($type,$id))){
			return false;
		}
		if(!($this->alreadySend($type,$id))){
			return false;
		}
		$bname = $this->getBureauName($type,$id);
		if(count($bname[1]) != 0){
			return false;
		}
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET send = '0' WHERE fileid = " . $id .
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}

	private function alreadySetBureau($ot,$fid){
		$sql = "select COUNT(*) AS num from " . $this->globalConfig->getSqlTable('sendtobureau') . 
			" WHERE  officetypeid = $ot AND fileid = $fid";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> sendedoffice.php 364");
		}
		while($row = $result->fetchRow()){
			$num = $row['num'];
		}
		if($num == 0){
			return false;
		}
		return true;
	}
}
?>