<?php
		/***************************************************************************
		                                office.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: office.php,v 1.8 2006/03/31 03:38:05 brgd Exp $
		
		 ***************************************************************************/
abstract class  office{
	protected $globalConfig;
	
	private static $num = Array();
	
	protected function office($obj){
		$this->setGlobalConfig($obj);
	}
	
	protected static function state($obname){
		$snum = office::$num;
		$arraynum = count($snum);
		
		for($i = 0; $i < $arraynum; $i++){
			if($snum[$i] == $obname){
				return false;
			}
		}
		$snum[$arraynum] = $obname;
		office::$num = $snum;
		return true;
	}
	
	public function displayCreateResult($template,$ok){
		$lang = $this->globalConfig->getXxbLang();;
		$tempname = $this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('officecreated');
		$template->loadTemplateFile($tempname . $this->globalConfig->getGlobal('htmlfileext'));
		$oback = '<a href="?target=' . $this->globalConfig->getGet('target') . '" id="officeback"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('back') . '</div></div></a>';
		
		$template->setCurrentBlock("block1");
 			$template->setVariable(array(
				"oback"  => $oback,
				"ok"     => '<div id="owarn">' . $ok . '</div>',	
			));
		$template->parseCurrentBlock("block1");
	}

	public function getTheBureauName($uid){
		$sql = "SELECT b.name FROM " . 
				$this->globalConfig->getSqlTable('bureau') . " AS b, " . 
				$this->globalConfig->getSqlTable('user') . " AS u " . 
				"WHERE  b.bureauid = u.bureauid " . " 
				AND u.userid = $uid";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
		$k = 0;
		while($row = $result->fetchRow()){
			$name[$k] = $row['name'];
			$k++;
		}
		return $name[0];
	}

	public function getBureauName($type, $id){ //get received and unreceived office file bureau name. 
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$sql = "SELECT bureauid FROM " . $this->globalConfig->getSqlTable('sendtobureau') . 
				" WHERE officetypeid = $type AND fileid = $id";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 104");
		}
		
		$k = 0;
		while($row = $result->fetchRow()){
			$r[$k] = $row['bureauid'];
			$k++;
		}
			
		$sql = "SELECT * FROM " . $this->globalConfig->getSqlTable('bureau') . 
			" WHERE bureauid <> " . $userinfo['bureauid'];
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 117");
		}
		
		$k = 0;$x = 0;
		while($row = $result->fetchRow()){
			$received = false;
			for($i = 0; $i < count($r); $i++){
				if($row['bureauid'] == $r[$i]){
					$received = true;
				}
			}
			if($received){
				$ur[1][$k] = $row;
				$k++;
			}else{
				$ur[0][$x] = $row;
				$x++;
			}
		}
	
		return $ur;
	}

	public function displayAllOfficeFile($template,$lang){
		$ftemp = $this->getOfficeFile($lang);
		
		$newvalue  = '<a href="?target=' . $this->globalConfig->getGet('target') . '&do=1"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('newoffice') . '</div></div></a>';
		
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('viewoffice') . $this->globalConfig->getGlobal('htmlfileext'));
			
		$template->setCurrentBlock("block0");
			$template->setVariable("newvalue",  $newvalue);
			$template->setVariable(array(
				"viewscript"      => $lang->getXxbLang('delscript'),
				"typetitle"       => $lang->getXxbLang('officefile'),
				"lurgencydegree"  => $lang->getXxbLang('lurgencydegree'),
				"lofficetype"     => $lang->getXxbLang('selecttype'),
				"ltitle"          => $lang->getXxbLang('title'),
				"lpubtime"        => $lang->getXxbLang('pubtime'),
				"ledit"           => $lang->getXxbLang('edit'),
			));
			if(count($ftemp) == 0){	
				$template->setCurrentBlock("block02");
					$template->setVariable(array(
						"noofficefile"  => $lang->getXxbLang('noofficefile'),
					));
				$template->parseCurrentBlock("block02");
			}else{
				$template->setCurrentBlock("block01");
				$j = 0;
				for($i = 0; $i < 3; $i++){
					for($z = 0; $z < count($ftemp[$i]); $z++){
						if(($j++)%2 == 0){$trbgcolor = 'otrbgcolor1';}else{$trbgcolor = 'otrbgcolor2';}
						$template->setVariable(array(
							"trbgcolor"     => $trbgcolor,
							"urgencydegree" => $ftemp[$i][$z]['lurgencydegree'],
							"officetype"    => $ftemp[$i][$z]['lofficetypename'],
							"title"         => $ftemp[$i][$z]['ltitle'],
							"pubtime"       => $ftemp[$i][$z]['lpubtime'],
							"send"          => $ftemp[$i][$z]['lsend'],
							"edit"          => $ftemp[$i][$z]['ledit'],
							"delete"        => $ftemp[$i][$z]['ldelete'],
						));
						$template->parseCurrentBlock("block01");
					}
				}
			}
			
		$template->parseCurrentBlock("block0");
	}	
	
	protected function getFileFreezeState($type,$id){
		$state = false;
		
		$sql = "SELECT freezed FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE fileid = " . $id . " AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 147");
		}
		
		$tempval = $result->fetchRow();
		if($tempval['freezed'] != '0'){
			$state = true;
		}
		
		return $state;
	}

	protected function ifCanModifyAnoldFile($type,$id){
		if(!($this->ifIsTheOwner($type,$id))){
			return false;
		}
		if($this->alreadyDeleted($type,$id)){
			return false;
		}
		if($this->alreadySend($type,$id)){
			return false;
		}
		return true;
	}

	protected function getOfficeTypeTableName($type){
		$sql = "SELECT ictbname FROM " . $this->globalConfig->getSqlTable('officetype') . 
				" WHERE officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 176");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}

		$tempval = $result->fetchRow();
		return $tempval['ictbname'];
	}

	protected function getAOfficeTypeName($typeid){
		$sql = "SELECT name FROM " . $this->globalConfig->getSqlTable('officetype') . 
				" WHERE officetypeid = " . $typeid;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 192");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}

		$tempval = $result->fetchRow();
		return $tempval['name'];
	}

	protected function getAOfficeFile($ot,$id){
		$type = $this->globalConfig->getGet('type');
		
		$sql = "SELECT * FROM $ot WHERE fileid = $id ";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 252");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}
		
		$tempval = $result->fetchRow();
		return $tempval;
	}

	protected function ifIsTheOwner($type,$id){
		$name = $this->getOfficeTypeTableName($type);
		if(!$name){
			return false;
		}
	
		$sql = "SELECT userid FROM " . $name . " WHERE fileid = " . $id;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 272");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}
		
		$tempval = $result->fetchRow();
		if($tempval['userid'] != $this->globalConfig->getXxbUser()->getUserinfo('userid')){
			return false;
		}

		return true;
	}

	protected function getOfficeTypeInfo(){
		$sql = "SELECT * FROM " . $this->globalConfig->getSqlTable('officetype');
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 231");
		}
		
		$i = 0;
		while($row = $result->fetchRow()){
			$tempval[$i]['officetypeid'] = $row['officetypeid'];
			$tempval[$i]['name']         = $row['name'];
			$tempval[$i]['ictbname']     = $row['ictbname']; 
			$tempval[$i]['description']  = $row['description'];
			$i++;
		}
		
		return $tempval;
	}
	
	protected function ifFileExist($oft,$title){
		$sql = "SELECT * FROM " . $oft . " WHERE title = \"" . $title . "\"";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 291");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}else{
			return true;
		}
	}

	public function ifCanDisplayAFile(){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if(!$this->ifIsTheOwner($type,$id) || 
				$this->alreadyDeleted($type,$id) || 
				$this->alreadySend($type,$id)){
			return false;
		}
		return true;
	}

	protected function getOfficeFileTypeName($ot){
		$sql = "SELECT ictbname FROM " . $this->globalConfig->getSqlTable('officetype') . 
			" WHERE officetypeid = $ot";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 318");
		}		
		
		$row  = $result->fetchRow();
		
		return $row['ictbname'];
	}

	protected function alreadyDeleted($type,$id){
		$sql = "SELECT deltime FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE fileid = " . $id . " AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 388");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}
		
		$tempval = $result->fetchRow();
		if($tempval['deltime'] != '0'){
			return true;
		}
		return false;
	}

	protected function alreadySend($type,$id){
		 //return true if office file send
		$sql = "SELECT send FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE fileid = " . $id . 
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> office.php 409");
		}
		$tempnum = $result->numRows();
		if($tempnum == 0){
			return false;
		}
		
		$tempval = $result->fetchRow();
		if($tempval['send'] != '0'){
			return true;
		}
		return false;
	}

	protected function setGlobalConfig($obj){
		$this->globalConfig = $obj;
	}
	
	protected function ifIsTheReceiver($type,$id){
		$sql = "SELECT sb.bureauid, of.freezed FROM " . $this->globalConfig->getSqlTable('sendtobureau') . " AS sb, " . 
				$this->globalConfig->getSqlTable('officefile') . " AS of " . 
				" WHERE sb.officetypeid = of.officetypeid " . 
				" AND sb.fileid = of.fileid" . 
				" AND of.officetypeid = $type" . 
				" AND of.fileid = $id" . 
				" AND of.freezed <> '0'" . 
				" AND sb.firstread = '0'" . 
				" AND sb.bureauid = " . $this->globalConfig->getXxbUser()->getUserinfo('bureauid');
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> receivedoffice.php 174");
		}
		$tempnum = $result->numRows();
		
		if($tempnum == 0){
			return false;
		}
		return true;
	}

	protected function ifIsTheReceiverAndRead($type,$id){
		$sql = "SELECT sb.bureauid, of.freezed FROM " . $this->globalConfig->getSqlTable('sendtobureau') . " AS sb, " . 
				$this->globalConfig->getSqlTable('officefile') . " AS of " . 
				" WHERE sb.officetypeid = of.officetypeid " . 
				" AND sb.fileid = of.fileid" . 
				" AND of.officetypeid = $type" . 
				" AND of.fileid = $id" . 
				" AND of.freezed <> '0'" . 
				" AND sb.firstread <> '0'" . 
				" AND sb.bureauid = " . $this->globalConfig->getXxbUser()->getUserinfo('bureauid');
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> receivedoffice.php 196");
		}
		$tempnum = $result->numRows();
		
		if($tempnum == 0){
			return false;
		}
		return true;
	}
	
	private function getOfficeFile($lang){
		$z = 0; $x = 0; $y = 0; $w = 0;
		$userinfo = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$ot = $this->getOfficeTypeInfo();
		
		for($i = 0; $i < count($ot); $i++){
			$sql = "SELECT  of.officetypeid, f.fileid, f.urgencydegree, f.title, f.pubtime FROM " . $this->globalConfig->getSqlTable('officefile') . " AS of, " . $ot[$i]['ictbname'] . " AS f " . 
				"WHERE  of.officetypeid = " . $ot[$i]['officetypeid'] . " AND f.fileid = of.fileid AND f.userid = " . $userinfo['userid'] . " AND of.deltime = '0' AND of.send = '0'" .  
				" GROUP BY f.pubtime DESC";
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewoffice.php 118");
			}
			
			$tempnum = $result->numRows();
			
			while($tempnum != 0)	{
				$j = 0;
				$f = null;
				while($row = $result->fetchRow()){
					$f[$j]['fileid']        =  $row['fileid'];
					$f[$j]['urgencydegree'] =  $row['urgencydegree'];
					$f[$j]['title']         =  $row['title'];
					$f[$j]['pubtime']       =  $row['pubtime'];
					$j++;
				}
				
				for($j = 0; $j < count($f); $j++){
					$doedit   = 3;
					$dotitle  = 2;
					$dodelete = 6;
					$dosend   = 7;
										
					$tempm[0] = '<div style="color:#f00">' . $f[$j]['urgencydegree'] . '</div>';
 					$tempm[1] = $ot[$i]['name'];
 					$tempm[2] = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$dotitle\"><div class=\"lhref\">" . $f[$j]['title'] . "</div></a>";
 					$tempm[3] = '<div class="liheight">' . date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'), $f[$j]['pubtime']) . '</div>';
					$tempm[4] = "<a href=\"#\" onclick=\"delconfirm('" . $lang->getXxbLang('ifsend') . "','?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$dosend')\"><div class=\"href\">" . $lang->getXxbLang('readysend') . "</div></a>";;
					$tempm[5] = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$doedit\"><div class=\"href\">" . $lang->getXxbLang('modify') . "</div></a>";
					$tempm[6] = "<a href=\"#\" onclick=\"delconfirm('" . $lang->getXxbLang('ifdelete') . "','?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$dodelete')\"><div class=\"href\">" . $lang->getXxbLang('delete') . "</div></a>";
					
					$temp = $lang->getXxbLang('urgencydegree');
					if($f[$j]['urgencydegree'] == $temp[2]){
 						$tofile[0][$w]['lurgencydegree']  = $tempm[0];
 						$tofile[0][$w]['lofficetypename'] = $tempm[1];
 						$tofile[0][$w]['ltitle']          = $tempm[2];
 						$tofile[0][$w]['lpubtime']        = $tempm[3];
						$tofile[0][$w]['lsend']           = $tempm[4];
						$tofile[0][$w]['ledit']           = $tempm[5];
						$tofile[0][$w]['ldelete']         = $tempm[6];
 						$w++;
 					}
 				
 					if($f[$j]['urgencydegree'] == $temp[1]){
 						$tofile[1][$y]['lurgencydegree']  = $tempm[0];
 						$tofile[1][$y]['lofficetypename'] = $tempm[1];
 						$tofile[1][$y]['ltitle']          = $tempm[2];
 						$tofile[1][$y]['lpubtime']        = $tempm[3];
						$tofile[1][$y]['lsend']           = $tempm[4];
						$tofile[1][$y]['ledit']           = $tempm[5];
						$tofile[1][$y]['ldelete']         = $tempm[6];
						$y++;
 					}
 				
 					if(($f[$j]['urgencydegree'] == $temp[0]) ||
						($f[$j]['urgencydegree'] == '')){
 						$tofile[2][$x]['lurgencydegree']  = $temp[0];
 						$tofile[2][$x]['lofficetypename'] = $tempm[1];
 						$tofile[2][$x]['ltitle']          = $tempm[2];
 						$tofile[2][$x]['lpubtime']        = $tempm[3];
 						$tofile[2][$x]['lsend']           = $tempm[4];
						$tofile[2][$x]['ledit']           = $tempm[5];
						$tofile[2][$x]['ldelete']         = $tempm[6];
						$x++;
 					}
				}
				$tempnum = 0;
			}
		}
		return $tofile;
	}
}
?>