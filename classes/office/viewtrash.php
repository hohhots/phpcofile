<?php
		/***************************************************************************
		                                viewtrash.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: viewtrash.php,v 1.6 2006/03/31 03:38:05 brgd Exp $
		
		 ***************************************************************************/
//display all office files deleted by ouself

include_once("classes/office/office.php");

class viewtrash extends office{
	private function viewtrash($obj){ //Constructor
		parent::__construct( $obj);
	}
	
	public static function makeObj($obj){
		if(self::state('viewtrash')){
			return new  viewtrash($obj);
		}else{
			exit('Just can make only one viewtrash object!');
		}
	}

	public function displayAllOfficeTrash($template,$lang){
		$ftemp = $this->getOfficeDeletedFile($lang);
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('viewtrash') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("block0");
		$template->setVariable(array(
			"viewscript"      => $lang->getXxbLang('delscript'),
			"typetitle"       => $lang->getXxbLang('trash'),
			"lurgencydegree"  => $lang->getXxbLang('lurgencydegree'),
			"lofficetype"     => $lang->getXxbLang('selecttype'),
			"ltitle"          => $lang->getXxbLang('title'),
			"ldeltime"        => $lang->getXxbLang('deltime'),
			"ledit"           => $lang->getXxbLang('edit'),
		));
		if(count($ftemp) == 0){	
			$template->setCurrentBlock("block02");
				$template->setVariable(array(
					"noofficefile"  => $lang->getXxbLang('nodeletedofficefile'),
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
					"deltime"       => date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'), $ftemp[$z]['ldeltime']),
					"undo"          => $ftemp[$z]['lundo'],
					"delete"        => $ftemp[$z]['ldelete'],
				));
				$template->parseCurrentBlock("block01");
			}
		}
		$template->parseCurrentBlock("block0");	
	}

	public function displayADeletedOfficeFile($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if(!$this->ifIsTheOwner($type,$id) || 
			!$this->alreadyDeleted($type,$id)){
			$ok = $lang->getXxbLang('nofile');
			$this->displayCreateResult($template, $ok);
		}else{
			$officetype = $this->globalConfig->getXxbUser()->getOfficeObject();
			$officetype->displayAFile($template,$lang);
		}
	}

	public function undoADeletedOfficeFile($template,$lang){
		if($this->setUndeleteMark()){
			$this->displayAllOfficeTrash($template,$lang);
		}else{
			$ok = $lang->getXxbLang('officeundofailed');
			$this->displayCreateResult($template, $ok);
		}
	}

	public function delAOfficeFileEver($template,$lang){
		if($this->delAOfficeFileEverInDB()){
			$this->displayAllOfficeTrash($template,$lang);
		}else{
			$ok = $lang->getXxbLang('officedeleteeverfailed');
			$this->displayCreateResult($template, $ok);
		}
	}

	private function setUndeleteMark(){
		$type = $this->globalConfig->getGet('type');
		$id = $this->globalConfig->getGet('id');
		
		if(!($this->ifIsTheOwner($type,$id))){
			return false;
		}
		if(!($this->alreadyDeleted($type,$id))){
			return false;
		}
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET deltime = '0' WHERE fileid = " . $id .
				" AND officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> viewtrash.php 116");
		}
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	private function getOfficeDeletedFile($lang){
		$x = 0;
		$userinfo = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$ot = $this->getOfficeTypeInfo();
		
		for($i = 0; $i < count($ot); $i++){
			$sql = "SELECT  of.officetypeid, f.fileid, f.urgencydegree, f.title, of.deltime FROM " . $this->globalConfig->getSqlTable('officefile') . " AS of, " . $ot[$i]['ictbname'] . " AS f " . 
				"WHERE  of.officetypeid = " . $ot[$i]['officetypeid'] . " AND f.fileid = of.fileid AND f.userid = " . $userinfo['userid'] . " AND of.deltime <> '0' " .  
				" GROUP BY of.deltime DESC";
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 137");
			}
			
			$tempnum = $result->numRows();
			
			while($tempnum != 0){
				$k = 0;
				$f = null;
				while($row = $result->fetchRow()){
					$f[$k]['fileid']        =  $row['fileid'];
					$f[$k]['urgencydegree'] =  $row['urgencydegree'];
					$f[$k]['title']         =  $row['title'];
					$f[$k]['deltime']       =  $row['deltime'];
					$k++;
				}
				
				for($j = 0; $j < count($f); $j++){
					$display = 1;
					$undo    = 2;
					$delete  = 3;

					$tofile[$x]['lofficetypename'] = $ot[$i]['name'];
 					$tofile[$x]['ltitle']          = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$display\"><div class=\"lhref\">" . $f[$j]['title'] . "</div></a>";
 					$tofile[$x]['ldeltime']        = $f[$j]['deltime'];
					$tofile[$x]['lundo']           = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$undo\"><div class=\"href\">" . $lang->getXxbLang('undo') . "</div></a>";
					$tofile[$x]['ldelete']         = "<a href=\"#\" onclick=\"delconfirm('" . $lang->getXxbLang('ifdeleteever') . "','?target=" . $this->globalConfig->getGet('target') . "&type=" . $ot[$i]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$delete')\"><div class=\"delete\" >" . $lang->getXxbLang('delete') . "</div></a>";

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
					++$x;
				}
				$tempnum = 0;
			}
			
		}
		return $tofile;
	}

	private function delAOfficeFileEverInDB(){
		$type = $this->globalConfig->getGet('type');
		$id = $this->globalConfig->getGet('id');

		if(!($this->ifIsTheOwner($type,$id))){
			return false;
		}
		if(!($this->alreadyDeleted($type,$id))){
			return false;
		}
		
		$sql = "SELECT MAX(fileid) AS fileid FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE officetypeid = " . $type;
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> viewtrash.php 200");
		}	
		
		$maxid = $result->fetchRow();$maxfileid = $maxid['fileid'];
		if($id == $maxfileid){
			$sql = "DELETE FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE officetypeid = " . $type . " AND fileid = " . $id;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 209");
			}
			
			$typename = $this->getOfficeFileTypeName($type);
			$sql = "DELETE FROM " . $typename . 
				" WHERE fileid = " . $id;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 217");
			}
			
			return true;
		}else{
			$sql = "DELETE FROM " . $this->globalConfig->getSqlTable('officefile') . 
				" WHERE officetypeid = " . $type . " AND fileid = " . $id;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 226");
			}
			$sql = "UPDATE "  . $this->globalConfig->getSqlTable('officefile') . 
				" SET fileid = '" . $id . 
				"' WHERE fileid = " . $maxfileid .
				" AND officetypeid = " . $type;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 234");
			}
			
			$typename = $this->getOfficeFileTypeName($type);
			$sql = "DELETE FROM " . $typename . 
				" WHERE fileid = " . $id;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 242");
			}
			$sql = "UPDATE "  . $typename . 
				" SET fileid = '" . $id . 
				"' WHERE fileid = " . $maxfileid;
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> viewtrash.php 249");
			}
			
			return true;
		}
	}
}
?>