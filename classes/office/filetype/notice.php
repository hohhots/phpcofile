<?php
		/***************************************************************************
		                                notice.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id$
		
		 ***************************************************************************/
include_once("classes/office/office.php");

class notice extends office{
	private function notice($obj){ //Constructor
		parent::__construct($obj);
	}
	
	public static function makeObj($obj){
		if(self::state('notice')){
			return new notice($obj);
		}else{
			exit('Just can make only one notice object!');
		}
	}
	
	public function displayANewFile($template,$lang){
		if(ereg("^[1-9]{1}[0-9]*$",$this->globalConfig->getGet('id'))){
			
			if(!($this->ifCanModifyAnoldFile($this->globalConfig->getGet('type'),$this->globalConfig->getGet('id')))){
				$ok = $lang->getXxbLang('officemodifyfailed');
				$this->displayCreateResult($template, $ok);
				return;
			}
		}
		
		$template->loadTemplateFile($this->globalConfig->getDirName('officefilestype') . $this->globalConfig->getDirName('notice') . $this->globalConfig->getFileName('newfile') . $this->globalConfig->getGlobal('htmlfileext'));
		
		$template->setCurrentBlock("block1");
		
		$preview =  '<a href="#"  onclick="allsubmit(onotice)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('preview') . '</div></div></a>';
		$reset =  '<a href="#"  onclick="allreset(onotice)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('rewrite') . '</div></div></a>';
		
		$template->setVariable(array(
			"preview"  => $preview,
			"rewrite"  => $reset,
			"hao"      => $lang->getXxbLang('hao'),
			"fujian"   => $lang->getXxbLang('fujian'),
			"zhutici"  => $lang->getXxbLang('zhutici'),
			"chaosong" => $lang->getXxbLang('chaosong'),
			"yinfa"    => $lang->getXxbLang('yinfa'),
		));

		$action = '?target=' . $this->globalConfig->getGet('target') . '&do=4&type=' . $this->globalConfig->getGet('type');
		if(ereg("^[1-9]{1}[0-9]*$",$this->globalConfig->getGet('id'))){
			$action .= '&id=' . $this->globalConfig->getGet('id');
			$this->modifyOldFile($action,$template,$lang);
		}else{
			if(count($this->globalConfig->getAllPost()) == 0){
				$this->absoluteNewFile($action,$template,$lang);
				
 			}else{
				$this->modifyNewFile($this->globalConfig->getAllPost(),$action,$template,$lang);
 			}	
 		}	
 		$template->parseCurrentBlock("block1");
	}

	public function displayAFile($template,$lang){
		$temp = false;
		
		$tempname = $this->globalConfig->getDirName('officefilestype') . $this->globalConfig->getDirName('notice') . $this->globalConfig->getFileName('afile');
		
		$temp = $this->getAOfficeFile($this->globalConfig->getSqlTable('onotice'),$this->globalConfig->getGet('id'));
			
 		if(!$temp){
			$ok = $lang->getXxbLang('nofile');
			$this->displayCreateResult($template, $ok);
 		}else{
			$template->loadTemplateFile($tempname . $this->globalConfig->getGlobal('htmlfileext'));
			$template->setVariable(array(
				"oback"  => '<a href="?target=' . $this->globalConfig->getGet('target') . '"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('back') . "</div></div></a>",
				"notice" => $lang->getXxbLang('view') . $lang->getXxbLang('notice'),
			));
			$this->displayContent($temp,$template,$lang);
 		}
	}

	public function checkANewFile($template,$lang){
		$tempname = $this->globalConfig->getDirName('officefilestype') . $this->globalConfig->getDirName('notice') . $this->globalConfig->getFileName('check');
		if($this->globalConfig->getPost('yearmonth') == $lang->getXxbLang('month')){
			$ge = $lang->getXxbLang('ge');
		}
		
		$actionputin  = '?target=' . $this->globalConfig->getGet('target') . '&do=' . ($this->globalConfig->getGet('do') + 1) . '&type=' . $this->globalConfig->getGet('type');
		$actionmodify = '?target=' . $this->globalConfig->getGet('target') . '&do=' . ($this->globalConfig->getGet('do') - 1) . '&type=' . $this->globalConfig->getGet('type');
		
		//$question     = $lang->getXxbLang('ifsavenew');
		if($this->globalConfig->getGet('id') != ''){
			$actionputin  .= '&id=' . $this->globalConfig->getGet('id');
			$actionmodify .= '&id=' . $this->globalConfig->getGet('id');
		}
		
		$template->loadTemplateFile($tempname . $this->globalConfig->getGlobal('htmlfileext'));
		$template->setVariable(array(
				"notice"        => $lang->getXxbLang('preview') . $lang->getXxbLang('notice'),
				"actionputin"   => $actionputin,
				"actionmodify"  => $actionmodify,
				"save"        =>  '<a href="#"  onclick="allconfirm(\'' . $lang->getXxbLang('ifsavenew') . '\',savefile)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('save') . '</div></div></a>',
				"modify"     =>  '<a href="#"  onclick="allsubmit(modifyfile)"><div class="boborder"><div class="biborder">' . $lang->getXxbLang('modify') . '</div></div></a>',
		));	

		$this->displayContent($this->globalConfig->getAllPost(),$template,$lang);
	}

	public function createANewFile($template,$lang){
		if($this->globalConfig->getGet('id') != ''){
			if($this->modifyFileInDb()){
				$this->displayAllOfficeFile($template,$lang);
				return;
			}else{
				$ok = $lang->getXxbLang('officemodifiedfail');
				$this->displayCreateResult($template, $ok);
			}
		}else{
			if($this->ifFileExist($this->globalConfig->getSqlTable('onotice'),$this->globalConfig->getPost('title'))){
				$ok    = $lang->getXxbLang('officecreatedfail');
				$this->displayCreateResult($template, $ok);
			}else{			
				$this->putInDb(); 
				$this->displayAllOfficeFile($template,$lang);
				return;
			}
		}
	}

	private function detePostValue($lang,$pname,$lname){
		if(trim($pname) == ''){
			$temp = $lang->getXxbLang($lname);
		}else{
			$temp = $pname;
		}
		return $temp;
	}

	private function modifyFileInDb(){
		$sql = "UPDATE "  . $this->globalConfig->getSqlTable('onotice') . 
				" SET secretlevel = '" . $this->globalConfig->getPost('secretlevel') . "'," . 
				"lasttime         = '" . $this->globalConfig->getPost('lasttime') . "'," . 
				"yearmonth        = '" . $this->globalConfig->getPost('yearmonth') . "'," . 
				"urgencydegree    = '" . $this->globalConfig->getPost('urgencydegree') . "'," . 
				"bureauname       = '" . $this->deKeepState($this->globalConfig->getPost('bureauname')) . "'," . 
				"agencyname       = '" . $this->globalConfig->getPost('agencyname') . "'," . 
				"year             = '" . $this->globalConfig->getPost('year') . "'," . 
				"ordernum         = '" . $this->globalConfig->getPost('ordernum') . "'," . 
				"title            = '" . $this->deKeepState($this->globalConfig->getPost('title')) . "'," . 
				"content          = '" . $this->deKeepState($this->globalConfig->getPost('content')) . "'," . 
				"accessories      = '" . $this->deKeepState($this->globalConfig->getPost('accessories')) . "'," . 
				"validatetime     = '" . $this->globalConfig->getPost('validatetime') . "'," . 
				"annotation       = '" . $this->globalConfig->getPost('annotation') . "'," . 
				"keywords         = '" . $this->globalConfig->getPost('keywords') . "'," . 
				"sendnames        = '" . $this->globalConfig->getPost('sendnames') . "'," . 
				"printbureau      = '" . $this->globalConfig->getPost('printbureau') . "'," . 
				"printdate        = '" . $this->globalConfig->getPost('printdate') . "' " . 
				" WHERE fileid = " . $this->globalConfig->getGet('id') . 
				" AND userid = " . $this->globalConfig->getXxbUser()->getUserinfo('userid');
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

	private function putInDb(){
		$sql = "SELECT MAX(fileid) AS fileid FROM " . $this->globalConfig->getSqlTable('onotice');
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
		$max = $result->fetchRow();
		
		$ttemp = $this->globalConfig->getSystemTime();
		++$max['fileid'];
		$sql =  "INSERT INTO " . $this->globalConfig->getSqlTable('onotice') . "
				(fileid,userid,secretlevel,lasttime,yearmonth,urgencydegree,bureauname,agencyname,year,ordernum,title,content,accessories,
				validatetime,annotation,keywords,sendnames,printbureau,printdate,pubtime 
				) Values (" . 
					$max['fileid'] . "," . 
					$this->globalConfig->getXxbUser()->getUserinfo('userid') . ",'" .
					$this->globalConfig->getPost('secretlevel')  . "','" .
					$this->globalConfig->getPost('lasttime') . "','" .
					$this->globalConfig->getPost('yearmonth') . "','" .
					$this->globalConfig->getPost('urgencydegree') . "','" .
					$this->deKeepState($this->globalConfig->getPost('bureauname'))  . "','" .  
			    		$this->globalConfig->getPost('agencyname')  . "','" .
			    		$this->globalConfig->getPost('year')  . "','" .
			    		$this->globalConfig->getPost('ordernum')  . "','" .
					$this->deKeepState($this->globalConfig->getPost('title')) . "','" .
			    		$this->deKeepState($this->globalConfig->getPost('content')) . "','" .
					$this->deKeepState($this->globalConfig->getPost('accessories'))  . "','" .
					$this->globalConfig->getPost('validatetime') . "','" .
					$this->globalConfig->getPost('annotation') . "','" .
					$this->globalConfig->getPost('keywords')  . "','" .
					$this->globalConfig->getPost('sendnames') . "','" .
					$this->globalConfig->getPost('printbureau')  . "','" .
					$this->globalConfig->getPost('printdate') . "','" .
					$ttemp . "'"				
				. ")";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}

		$sql = "INSERT INTO " . $this->globalConfig->getSqlTable('officefile') .
			"(officetypeid,fileid) Values (" . 
			 $this->globalConfig->getGet('type') . "," . $max['fileid'] . ")";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {
			die ($result->getMessage());
		}
	}

	private function absoluteNewFile($action,$template,$lang){
		$template->setVariable(array(
			"notice"        => $lang->getXxbLang('new') . $lang->getXxbLang('notice'),
			"action"        => $action,
 			"lasttime"      => $lang->getXxbLang('lasttime'),
			"bureauname"    => $lang->getXxbLang('bureauname'),    
			"agencyname"    => $lang->getXxbLang('agencyname'),
			"year"          => $lang->getXxbLang('oyear'),
			"ordernum"      => $lang->getXxbLang('ordernum'),
			"title"         => $lang->getXxbLang('noticetitle'),
			"content"       => $lang->getXxbLang('noticecontent'),
			"accessories"   => $lang->getXxbLang('accessories'),
			"validatetime"  => $this->globalConfig->getGlobal('cChiDate'),
			"annotation"    => $lang->getXxbLang('fuzhu'),
			"keywords"      => $lang->getXxbLang('zhutici2'),
			"sendnames"     => $lang->getXxbLang('chaosong2'),
			"printbureau"   => $lang->getXxbLang('yinfajiguan'),
			"printdate"     => $this->globalConfig->getGlobal('cNumDate'),
		));
 				
 		$template->setCurrentBlock("block11");
 		$temp = $lang->getXxbLang('secretlevel');
		for($i = 0; $i < count($temp); $i++){
			if($i == 0){$s = 'selected';}else{$s = '';}
			$template->setVariable(array(
				"s"       => $s,
				"value"   => $temp[$i],
			));
			$template->parseCurrentBlock("block11");
		}
		
		$template->setCurrentBlock("block12");
 		for($i = 0; $i < 2; $i++){	
			if($i == 0){
				$s = 'selected';
				$temp = $lang->getXxbLang('month');
			}else{
				$s = '';
				$temp = $lang->getXxbLang('year');
			}
			$template->setVariable(array(
				"s"        => $s,
				"value"    => $temp,
			));
			$template->parseCurrentBlock("block12");
		}
		$template->setCurrentBlock("block13");
 		$temp = $lang->getXxbLang('urgencydegree');
 		for($i = 0; $i < count($temp); $i++){
			if($i == 0){$s = 'selected';}else{$s = '';}
			$template->setVariable(array(
				"s"               => $s,
				"value"         => $temp[$i],
			));
			$template->parseCurrentBlock("block13");
		}
	}

	private function modifyNewFile($values,$action,$template,$lang){
		if($this->globalConfig->getGet('id') == ''){
			$template->setVariable(array(
				"notice"   => $lang->getXxbLang('new') . $lang->getXxbLang('notice'),
			));
		}
		if($values['lasttime'] == ''){
			$tlasttime = $lang->getXxbLang('lasttime');
		}else{
			if(ereg("^[1-9]{1}[0-9]*$",trim($values['lasttime']))){
				$tlasttime = trim($values['lasttime']);
			}else{
				$tlasttime = $lang->getXxbLang('lasttime');
			}
		}

		$taccessories = $this->detePostValue($lang,$values['accessories'],'accessories');
		$tannotation  = $this->detePostValue($lang,$values['annotation'],'fuzhu');
		$tkeywords    = $this->detePostValue($lang,$values['keywords'],'zhutici2');
		$tsendnames   = $this->detePostValue($lang,$values['sendnames'],'chaosong2');
		$template->setVariable(array(
			"action"       => $action,
 			"lasttime"     => $tlasttime,
			"bureauname"   => $this->deKeepState($values['bureauname']),    
			"agencyname"   => $values['agencyname'],
			"year"         => $values['year'],
			"ordernum"     => $values['ordernum'],
			"title"        => $this->deKeepState($values['title']),
		  	"content"      => $this->deKeepState($values['content']),
			"accessories"  => $this->deKeepState($taccessories),
			"validatetime" => $values['validatetime'],
			"annotation"   => $tannotation,
			"keywords"     => $tkeywords,
			"sendnames"    => $tsendnames,
			"printbureau"  => $values['printbureau'],
			"printdate"    => $values['printdate'],
		));
		
		$template->setCurrentBlock("block11");
 		$secretlevel  =  $lang->getXxbLang('secretlevel');
 		
		$tboolean = false;
		for($i = 0; $i < count($secretlevel); $i++){
			if($secretlevel[$i] == $values['secretlevel']){
				$tboolean = true;
			}
		}
		for($i = 0; $i < count($secretlevel); $i++){
			if($tboolean == false){
				if($i == 0){
					$s = 'selected';
					$temp = $secretlevel[$i];
				}else{
					$s = '';
					$temp = $secretlevel[$i];
				}
			}else{
				if($secretlevel[$i] == $values['secretlevel']){
					$s = 'selected';
					$temp = $secretlevel[$i];
				}else{
					$s = '';
					$temp = $secretlevel[$i];
				}
			}
			
			$template->setVariable(array(
				"s"       => $s,
				"value"   => $temp,
			));
 			$template->parseCurrentBlock("block11");
		}

		//Begin set year month
		$tboolean = false;
		if(($values['yearmonth'] == $lang->getXxbLang('month')) || 
			($values['yearmonth'] == $lang->getXxbLang('year'))){
			$tboolean = true;
		}
		$template->setCurrentBlock("block12");
 			if($tboolean == false){
				$s = 'selected';
				$temp = $lang->getXxbLang('month');
				$template->setVariable(array(
					"s"       => $s,
					"value"   => $temp,
				));
				$template->parseCurrentBlock("block12");
				$s = '';
				$temp = $lang->getXxbLang('year');
				$template->setVariable(array(
					"s"       => $s,
					"value"   => $temp,
				));
				$template->parseCurrentBlock("block12");
			}else{
				if($values['yearmonth'] == $lang->getXxbLang('month')){
					$s = 'selected';
				}else{
					$s = '';
				}
				$temp = $lang->getXxbLang('month');
				$template->setVariable("s", $s);
				$template->setVariable("value",$temp);
				$template->parseCurrentBlock("block12");
				if($values['yearmonth'] == $lang->getXxbLang('year')){
					$s = 'selected';
				}else{
					$s = '';
				}
				$temp = $lang->getXxbLang('year');
				$template->setVariable("s", $s);
				$template->setVariable("value",$temp);
				$template->parseCurrentBlock("block12");
			}
		
		//End set year month

		$template->setCurrentBlock("block13");
 		$temp = $lang->getXxbLang('urgencydegree');

		$tboolean = false;
		for($i = 0; $i < count($temp); $i++){
			if($temp[$i] == $values['urgencydegree']){
				$tboolean = true;
			}
		}

		for($i = 0; $i < count($temp); $i++){
			if($tboolean == false){
				if($i == 0){
					$s = 'selected';
					$temp2 = $temp[$i];
				}else{
					$s = '';
					$temp2 = $temp[$i];
				}
			}else{
				if($temp[$i] == $values['urgencydegree']){
					$s = 'selected';
					$temp2 = $temp[$i];
				}else{
					$s = '';
					$temp2 = $temp[$i];
				}
			}
			
			$template->setVariable(array(
				"s"       => $s,
				"value"   => $temp2,
			));
 			$template->parseCurrentBlock("block13");
		}
	}

	private function modifyOldFile($action,$template,$lang){
		$type = $this->globalConfig->getGet('type');
		$id   = $this->globalConfig->getGet('id');
		
		if($this->ifCanModifyAnoldFile($type,$id)){
			$template->setVariable(array(
				"notice"   => $lang->getXxbLang('modify') . $lang->getXxbLang('notice'),
			));
			$temp = $this->getAOfficeFile($this->globalConfig->getSqlTable('onotice'),$this->globalConfig->getGet('id'));
			$this->modifyNewFile($temp,$action,$template,$lang);
		}else{
			$this->displayCreateResult($template,$oback,$ok);
		}
	}

	private function displayContent($values,$template,$lang){
		$ts = $lang->getXxbLang('secretlevel');
		if($values['secretlevel'] == $ts[0]){
			$tsecretlevel = '';$tsecretlevelh = $ts[0];
		}else{
			$tboolean = false;
			$tsecretlevel   = $values['secretlevel'];$tsecretlevelh  = $values['secretlevel'];
			for($i=1; $i < count($ts); $i++){
				if($values['secretlevel'] == $ts[$i]){
					if(ereg("^[1-9]{1}[0-9]*$",trim($values['lasttime']))){
						$tstar      = '&nbsp;бя&nbsp;';
						$tlasttime  = $values['lasttime'];
						$tge        = $ge;
						$tyearmonth = $values['yearmonth'];$tyearmonthh = $values['yearmonth'];
						$tboolean = true;
					}
				}
			}
		}
		if($tboolean == false){
			$tyearmonth  = '';$tyearmonthh = $lang->getXxbLang('month');
		}

		$tu = $lang->getXxbLang('urgencydegree');
		if($values['urgencydegree'] == $tu[0]){
			$turgencydegree = '';$turgencydegreeh = $tu[0];
		}else{
			$tboolean == false;
			for($i=1; $i < count($tu); $i++){
				if($values['urgencydegree'] == $tu[$i]){
					$turgencydegree = $values['urgencydegree'];$turgencydegreeh = $values['urgencydegree'];
					$tboolean = true;
					break;
				}
			}
			if($tboolean == false){
				$turgencydegree = '';$turgencydegreeh = $tu[0];
			}
		}

		if(ereg("^[ ]*$", $values['accessories']) ||
			($values['accessories'] == $lang->getXxbLang('accessories'))){
		}else{
			$taccessories1 = $lang->getXxbLang('fujian');
			$taccessories  = $values['accessories'];
		}

		if(ereg("^[ ]*$", $values['annotation']) ||
			($values['annotation'] == $lang->getXxbLang('fuzhu'))){
		}else{
			$tannotation = $values['annotation'];
			$tleftp = '(';	
			$trightp = ')';
		}

		if(ereg("^[ ]*$", $values['keywords']) ||
			($values['keywords'] == $lang->getXxbLang('zhutici2'))){
		}else{
			$tkeywordshr = '<hr size="1px" color="#000000" />';
			$tkeywords1 = $lang->getXxbLang('zhutici');	
			$tkeywords  = $values['keywords'];
		}

		if(ereg("^[ ]*$", $values['sendnames']) ||
			($values['sendnames'] == $lang->getXxbLang('chaosong2'))){
		}else{
			$tsendnameshr = '<hr size="1px" color="#000000" />';
			$tsendnames1  = $lang->getXxbLang('chaosong');	
			$tsendnames   = $values['sendnames'];
		}
		
		$template->setCurrentBlock("block1");
 			$template->setVariable(array(
					"secretlevel"   => $tsecretlevel,
					"secretlevelh"   => $tsecretlevelh,
					"star"          => $tstar,
					"lasttime"      => $tlasttime,
					"ge"            => $tge,
					"yearmonth"     => $tyearmonth,
					"yearmonthh"    => $tyearmonthh,
					"urgencydegree" => $turgencydegree,
					"urgencydegreeh"=> $turgencydegreeh,
					"bureauname"    => $this->keepState($values['bureauname']),    
		    			"agencyname"    => $values['agencyname'],
		    			"year"          => $values['year'],
		    			"hao"           => $lang->getXxbLang('hao'),
					"ordernum"      => $values['ordernum'],
					"title"         => $this->keepState($values['title']),
		    			"content"       => $this->keepState($values['content']),
					"accessories1"  => $taccessories1,
					"accessories"   =>  $this->keepState($taccessories),
					"validatetime"  => $values['validatetime'],
					"leftp"         => $tleftp,
					"annotation"    => $tannotation,
					"rightp"        => $trightp,
					"keywordshr"    => $tkeywordshr,
					"keywords1"     => $tkeywords1,
					"keywords"      => $tkeywords,
					"sendnameshr"   => $tsendnameshr,
					"sendnames1"    => $tsendnames1,
					"sendnames"     => $tsendnames,
					"printbureau"   => $values['printbureau'],
					"printdate"     => $values['printdate'],
					"yinfa"         => $lang->getXxbLang('yinfa'),
				));
		
 		$template->parseCurrentBlock("block1");
	}

	private function keepState($values){
		$temp = str_replace('  ', '&nbsp; ', $values);   //two spaces replace with a &nbsp; and a space;
		$temp = str_replace("\t", '&nbsp; &nbsp; &nbsp; &nbsp; ', $temp);
		$temp = nl2br($temp);
		
		return $temp;
	}
	
	private function deKeepState($values){
		$temp = str_replace('&lt;br /&gt;', '', $values);
		$temp = str_replace('&amp;#160;', ' ', $temp);
		$temp = str_replace('&amp;nbsp;', ' ', $temp);
		
		return $temp;
	}
}
?>
