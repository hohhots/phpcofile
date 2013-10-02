<?php
		/***************************************************************************
		                                receivedoffice.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: receivedoffice.php,v 1.7 2006/03/31 03:44:10 brgd Exp $
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class receivedoffice extends office{
	private function receivedoffice($obj){ //Constructor
		parent::__construct( $obj);
	}
	
	public static function makeObj($obj){
		if(self::state('receivedoffice')){
			return new  receivedoffice($obj);
		}else{
			exit('Just can make only one receivedoffice object!');
		}
	}

	public function displayAReceivedOffice($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if(!$this->ifIsTheReceiver($type,$id)){
			$ok = $lang->getXxbLang('nofile');
			$this->displayCreateResult($template, $ok);
		}else{
			$this->setAReceivedOfficeFileRead($template,$lang);
			$officetype = $this->globalConfig->getXxbUser()->getOfficeObject();
			$officetype->displayAFile($template,$lang);
		}
	}

	public function displayAllReceivedOffice($template,$lang){
		$ftemp = $this->getOfficeReceivedFile($lang);
		$template->loadTemplateFile($this->globalConfig->getDirName('office') . $this->globalConfig->getFileName('receivedoffice') . $this->globalConfig->getGlobal('htmlfileext'));
		$template->setCurrentBlock("block0");
		$template->setVariable(array(
			"confirmscript"   => $lang->getXxbLang('confirmscript'),
			"typetitle"       => $lang->getXxbLang('receivedoffice'),
			"lurgencydegree"  => $lang->getXxbLang('lurgencydegree'),
			"lsenduser"       => $lang->getXxbLang('senduser'),
			"ltitle"          => $lang->getXxbLang('title'),
			"lfreezetime"     => $lang->getXxbLang('sentime'),
		));
		if(count($ftemp) == 0){	
			$template->setCurrentBlock("block02");
				$template->setVariable(array(
					"noofficefile"  => $lang->getXxbLang('noreceivededofficefile'),
				));
			$template->parseCurrentBlock("block02");
		}else{
			$template->setCurrentBlock("block01");
			$j = 0;
			for($z = 0; $z < count($ftemp); $z++){
				if(($j++)%2 == 0){$trbgcolor = 'otrbgcolor1';}else{$trbgcolor = 'otrbgcolor1';}
				$template->setVariable(array(
					"trbgcolor"     => $trbgcolor,
					"urgencydegree" => $ftemp[$z]['lurgencydegree'],
					"senduser"      => $ftemp[$z]['lsenduser'],
					"title"         => $ftemp[$z]['ltitle'],
					"freezetime"    => date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'), $ftemp[$z]['lfreezetime'])
				));
				$template->parseCurrentBlock("block01");
			}
		}
		$template->parseCurrentBlock("block0");
	}

	private function setAReceivedOfficeFileRead($template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();

		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('sendtobureau') . 
				" SET firstread = " . $this->globalConfig->getSystemTime() . " WHERE fileid = " . $id .
				" AND officetypeid = " . $type . 
				" AND bureauid = " . $userinfo['bureauid'] . 
				" AND firstread = '0'";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage() . " --> receivedoffice.php 89");
		}
		$this->displayAllReceivedOffice($template,$lang);
	}

	private function getOfficeReceivedFile($lang){
		$x = 0;
		$userinfo   = $this->globalConfig->getXxbSerCookie()->getAllUserinfo();
		
		$ot = $this->getOfficeTypeInfo();
		$ttime = ($this->globalConfig->getSystemTime()) - ($this->globalConfig->getGlobal("lasttime"));
		for($i = 0; $i < count($ot); $i++){
			$sql = "SELECT of.officetypeid, f.fileid, f.userid, f.urgencydegree, f.title, of.freezed
				 FROM " . $this->globalConfig->getSqlTable('sendtobureau') . " AS sb, " . 
				$this->globalConfig->getSqlTable('officefile') . " AS of, " . 
				$ot[$i]['ictbname'] . " AS f " . 
				"WHERE  f.fileid = of.fileid " . " 
				AND of.officetypeid = sb.officetypeid 
				AND of.fileid = sb.fileid 
				AND of.officetypeid = " . $ot[$i]['officetypeid'] . "
				AND f.userid <> " . $userinfo['userid'] . " 
				AND sb.bureauid = " . $userinfo['bureauid'] . " 
				AND of.freezed <> '0' 
				AND (sb.firstread > $ttime
				OR sb.firstread = '0')
				GROUP BY of.freezed DESC";
			$result = $this->globalConfig->getXxbDatabase()->query($sql);
			if (DB::isError($result)) {
				die ($result->getMessage() . " --> receivedoffice.php 117");
			}
			
			$tempnum1 = $result->numRows();
			
			if($tempnum1 != 0){
				$k = 0;
				$f = null;
				while($row = $result->fetchRow()){
					$f[$k]['officetypeid']  =  $row['officetypeid'];
					$f[$k]['fileid']        =  $row['fileid'];
					$f[$k]['urgencydegree'] =  $row['urgencydegree'];
					$f[$k]['title']         =  $row['title'];
					$f[$k]['freezed']       =  $row['freezed'];
					$f[$k]['bname']         =  $this->getTheBureauName($row['userid']);
					$k++;
				}
				
				for($j = 0; $j < count($f); $j++){
					$display  = 1;
					
					$tofile[$x]['ltitle']         = "<a href=\"?target=" . $this->globalConfig->getGet('target') . "&type=" . $f[$j]['officetypeid'] . "&id=" . $f[$j]['fileid'] . "&do=$display\"><div class=\"lhref\">" . $f[$j]['title'] . "</div></a>";
 					$tofile[$x]['lsenduser']      = $f[$j]['bname'];
					$tofile[$x]['lurgencydegree'] = $f[$j]['urgencydegree'];
					$tofile[$x]['lfreezetime']    = $f[$j]['freezed'];
					
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

}
?>