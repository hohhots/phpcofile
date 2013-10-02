<?php

                /***************************************************************************
		                                config.**
		                             -----------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: config.php,v 1.7 2006/04/07 03:19:42 brgd Exp $
		
		 ***************************************************************************/	 
//sql sqltype,username,userpass   //redirector url
class globalConfig{
	private static $num = 0;
	
	private $systemTime;
	//Set variables
	private $xxbGlobal   = array();
	private $xxbCookie   = array();
	private $xxbDir      = array();
	private $xxbFile     = array();
	private $xxbLangFile = array();
	private $xxbDb = array();
	private $sqlUser = array();
	private $sqlTable = array();

	//Addslashes in  GPC values.
	private $get     = array();
	private $post    = array();
	private $cookie  = array();
	
	//Set some object variable
	private $xxbDatabase;    //interact with database
	private $xxbUser;           //Collect user all information
	private $xxbTemplate;    //Pear html template
	private $xxbLang;          //Language information
	private $xxbSerCookie; //have cookie information
	private $xxbFileSytem;   //interact with file system 
	private $txxbCommon;
	
	private $userType = array() ;

	//Constructor
	private function globalConfig(){
		$this->addSlashGpc();
		$this->initiolizeVar();
		$this->setGlobalLang();
	}
	
	public static function makeObj(){
		if(globalConfig::$num == 0){
			globalConfig::$num++;
			return new  globalConfig();
		}else{
			exit('Just can make only one globalConfig object!');
		}
	}
	
	public function getGlobal($name){
		return $this->xxbGlobal[$name];
	}
	
	public function getSystemTime(){
		return $this->systemTime;
	}
	
	public function getSqlUser($name){
		return $this->sqlUser[$name];
	}
	
	public function getSqlTable($name){
		return $this->sqlTable[$name];
	}
	
	public function getCookie($name){
		return $this->cookie[$name];
	}
	
	public function getGet($name){
		return $this->get[$name];
	}
	
	public function getAllGet(){
		return $this->get;
	}
	
	public function getPost($name){
		return $this->post[$name];
	}
	
	public function getAllPost(){
		return $this->post;
	}
	
	public function getUserType($name){
		return $this->userType[$name];
	}
	
	public function getXxbCookie($name){
		return $this->xxbCookie[$name];
	}
	
	public function getDirName($dir){
		return $this->xxbDir[$dir];
	}
	
    public function getFileName($file){
		return $this->xxbFile[$file];
	}
	
	public function getXxbLang(){
		return $this->xxbLang;
	}
	
	public function getXxbSerCookie(){
		return $this->xxbSerCookie;
	}
	
	public function getXxbDatabase(){
		return $this->xxbDatabase;
	}
	
	public function getXxbTemplate(){
		return $this->xxbTemplate;
	}
	
	public function redirector($url = "?target=1"){
		header('Location:' . $this->xxbGlobal['url'] . $url);
		exit();
	}
	
	public function getXxbUser(){
		return $this->xxbUser;
	}
	
	private function initiolizeVar(){
		$this->systemTime = time();
		$this->xxbGlobal['langtype']         = '1';   //1 simple chinese; 2 english
		$this->xxbGlobal['url']              = 'http://127.0.0.1/phpcofile/'; //Your domain name
		$this->xxbGlobal['phpfileext']       = '.php';
		$this->xxbGlobal['htmlfileext']      = '.html';
		$this->xxbGlobal['lasttime']         = '604800';//in received office file page,after this time,
							   //that readed files would be removed to collection.
	

		$this->xxbCookie['name']   = 'huhhotgovcnfile';
		$this->xxbCookie['path']   = '/';
		$this->xxbCookie['domain'] = '';
		$this->xxbCookie['secure'] = '0';
		$this->xxbCookie['length'] = '900';

		$this->sqlUser['DBtype'] = 'mysql';
		$this->sqlUser['user']   = 'phpcofile';
		$this->sqlUser['pass']   = '4e5r6t';
		$this->sqlUser['server'] = 'localhost';
		$this->sqlUser['db']     = 'xxbfile';
		
		//Set SQL table name
		$this->sqlTable['cookies']    = 'cookies';
		$this->sqlTable['user']       = 'user';
		$this->sqlTable['loginfo']    = 'loginfo';
		$this->sqlTable['officefile'] = 'officefile';
		$this->sqlTable['officetype'] = 'officetype';
		$this->sqlTable['bureau']     = 'bureau';
		$this->sqlTable['sendtobureau']= 'sendtobureau';
		$this->sqlTable['onotice']    = 'onotice';
					
		//Set dir name
		$this->xxbDir['class']    = 'classes/';         //Include all classes dir name
		$this->xxbDir['language'] = 'language/'; //Include all languages dir name
		$this->xxbDir['template'] = 'templates/'; //Include all html templates dir name
		$this->xxbDir['office']   = 'office/';
		$this->xxbDir['officefilestype'] = $this->xxbDir['office'] . 'filetype/';
		$this->xxbDir['notice']   = 'notice/';
			
		
		//Set file Name
		//classes name
		$this->xxbFile['pear']       = 'pear';
		$this->xxbFile['db']         = 'db';
		$this->xxbFile['html']       = 'html';
		$this->xxbFile['cookie']     = 'cookie';
		$this->xxbFile['langcomm']   = 'langcommon';
		$this->xxbFile['filesystem'] = 'filesystem';
		
		//set template file name
		$this->xxbFile['headtemp']      = 'head';
		$this->xxbFile['logintemp']     = 'login';
		$this->xxbFile['foottemp']      = 'foot';
		$this->xxbFile['helptemp']      = 'help';
		$this->xxbFile['hometemp']      = 'home';
		$this->xxbFile['receivedoffice']= 'receivedoffice';
		$this->xxbFile['sendedoffice']  = 'sendedoffice';
		$this->xxbFile['setoreceiver']  = 'setoreceiver';
		$this->xxbFile['officerecords'] = 'officerecords';
		$this->xxbFile['viewoffice']    = 'viewoffice';
		$this->xxbFile['viewtrash']     = 'viewtrash';
		$this->xxbFile['afile']         = 'afile';
		$this->xxbFile['newfile']       = 'newfile';
		$this->xxbFile['check']         = 'check';
		$this->xxbFile['officecreated'] = 'created';
		
		//Set office php file name
		$this->xxbFile['command']      = 'command';
		$this->xxbFile['decision']     = 'decision';
		$this->xxbFile['bulletin']     = 'bulletin';
		$this->xxbFile['encyclic']     = 'encyclic';
		$this->xxbFile['notice']       = 'notice';
		$this->xxbFile['aviso']        = 'aviso';
		$this->xxbFile['bill']         = 'bill';
		$this->xxbFile['reporting']    = 'reporting';
		$this->xxbFile['instructions'] = 'instructions';
		$this->xxbFile['reversion']    = 'reversion';
		$this->xxbFile['opinion']      = 'opinion';
		$this->xxbFile['cases']        = 'cases';
		$this->xxbFile['summary']      = 'summary';
		$this->xxbFile['others']       = 'others';
		
		//Set language file Name
		$this->xxbLangFile['scn']    = 'simplechinese';
		$this->xxbLangFile['en']     = 'english';
		
		//Set user level constants
		$this->userType['guest'] = 'G';
		$this->userType['user']  = 'U';
		$this->userType['admin'] = 'A';
		
		$this->ifRedirect();	//none cookie,just can visit homepage.
		
		//Include common used classes
		$this->includeFiles();
		
		$dsn = $this->sqlUser['DBtype'] . "://" . $this->sqlUser['user'] . ":" . $this->sqlUser['pass'] . "@" . $this->sqlUser['server']. "/" . $this->sqlUser['db'];
		
		$this->xxbDatabase = DB::connect($dsn,true); //second para for pconnect
		if (DB::isError($this->xxbDatabase)) {die ($this->xxbDatabase->getMessage());}
		$this->xxbDatabase->setFetchMode(DB_FETCHMODE_ASSOC);
		$this->xxbDatabase->query("SET CHARACTER SET gb2312");
		$this->xxbDatabase->query("SET collation_connection = 'gb2312_chinese_ci'");
		
		$this->xxbTemplate = HTML_Template_ITX::makeObj($this->xxbDir['template']);
		$this->xxbFileSytem = fileSystem::makeObj();
		$this->xxbSerCookie = cookie::makeObj($this);
		
		$this->setXxbUser();
		
		$this->xxbLang = xxbLanguage::makeObj($this);
		
		//formulated date
		$this->xxbGlobal['cNumDate'] = $this->getNumDate();
		$this->xxbGlobal['cChiDate'] = $this->getChiDate();
	}
	
	private function ifRedirect(){ //none cookie,just can visit homepage.
		if(isset($this->cookie[$this->xxbCookie['name']])){
			if(!isset($this->get['target'])){
				$this->redirector();
			}
		}else{
			if($this->get['target'] != 1){
				$this->redirector();
			}
		}
	}
	
	private function includeFiles(){
		$this->xxbInclude($this->xxbDir['class'] , $this->xxbFile['pear'] ,$this->xxbGlobal['phpfileext'] );
		$this->xxbInclude($this->xxbDir['class'] , $this->xxbFile['db'],$this->xxbGlobal['phpfileext'] );
		$this->xxbInclude($this->xxbDir['class'] , $this->xxbFile['html'] ,$this->xxbGlobal['phpfileext'] );
		$this->xxbInclude($this->xxbDir['class'] , $this->xxbFile['filesystem'] , $this->xxbGlobal['phpfileext'] );
		$this->xxbInclude($this->xxbDir['class'] , $this->xxbFile['cookie'] , $this->xxbGlobal['phpfileext'] );
				
		//Include language constant.
		$this->xxbInclude($this->xxbDir['language'] , '' , $this->xxbGlobal['phpfileext'] );
	}
	
	private function setXxbUser(){
		if($this->xxbSerCookie->getUserinfo('userid') == 0){
			$type = $this->userType['guest'];
		}else{
			$type = $this->xxbSerCookie->getUserinfo('usertype');
		}
		
		switch($type){
			case $this->userType['guest']:
				$this->xxbInclude(($this->xxbDir['class'] . "user/"), "guest" , $this->xxbGlobal['phpfileext'] );
				$this->xxbUser = Guest::makeObj($this);
				break;
			case $this->userType['user']:
				$this->xxbInclude(($this->xxbDir['class'] . "user/") , "user" , $this->xxbGlobal['phpfileext'] );
				$this->xxbUser = User::makeObj($this);
				break;
			case $this->userType['admin']:
				$this->xxbInclude(($this->xxbDir['class'] . "user/") , "admin" , $this->xxbGlobal['phpfileext'] );
				$this->xxbUser = Admin::makeObj($this);
				break;
		}
	}

	private function setGlobalLang(){
		if((isset($this->get['lang'])) && ($this->get['lang'] != 1)){
			switch($this->get['lang']){
				case 2:
					$this->xxbGlobal['langtype'] = '2';
					break;
			}
		}
	}
	
	private function addSlashGpc(){
		for($i = 0; $i < 3; $i++){
			switch($i){
				case 0:
					$gpc = $_GET;
					break;
				case 1:
					$gpc = $_POST;
					break;
				case 2:
					$gpc = $_COOKIE;
					break;
			}
			if(get_magic_quotes_gpc()){
				if( is_array($gpc)){
					while( list($k, $v) = each($gpc)){
						if( is_array($gpcvars[$k])){
							while( list($k2, $v2) = each($gpc[$k])){
								$$gpcvars[$k][$k2] = htmlspecialchars(stripslashes($v2), ENT_QUOTES);
							}
							@reset($gpc[$k]);
						}
						else{
							$gpc[$k] = htmlspecialchars(stripslashes($v), ENT_QUOTES);
						}
					}
					@reset($gpc);
				}
			}else{
				if(is_array($gpc)){
					while( list($k, $v) = each($gpc)){
						if( is_array($gpcvars[$k])){
							while(list($k2, $v2) = each($gpc[$k])){
								$$gpcvars[$k][$k2] = htmlspecialchars($v2, ENT_QUOTES);
							}
							@reset($gpc[$k]);
						}
						else{
							$gpc[$k] = htmlspecialchars($v, ENT_QUOTES);
						}
					}
					@reset($gpc);
				}
			}
			switch($i){
				case 0:
					$this->get = $gpc;
					break;
				case 1:
					$this->post = $gpc;
					break;
				case 2:
					$this->cookie = $gpc;
					break;
			}
		}
	}
	
	private function xxbInclude($dir,$file,$ext){
		if(($ext == $this->xxbGlobal['phpfileext']) || ($ext == $this->xxbGlobal['htmlfileext']) ){
			if($dir != $this->xxbDir['language'] ){
				$filename = ($dir . $file . $ext);
			}else{
				$filename = ($dir . $this->getLanguageFile() . $ext);
			}
				
			if(file_exists($filename)){
				require_once($filename);
			}else{
				exit('<font color="#ff0000" size="6">Can\'t find the file ' . $filename . '!</font>');
			}
		}
	}
	
	private function getLanguageFile(){
		switch($this->xxbGlobal['langtype']){
			case 1:
				$lang = $this->xxbLangFile['scn'];
				break;
			case 2:
				$lang = $this->xxbLangFile['en'];
				break;
			default:
				$lang = $this->xxbLangFile['scn'];
				break;
		}
		return $lang;
	}

	private function getNumDate(){
		$lang = $this->xxbLang;
		$tdate = date("Y" . $lang->getXxbLang('year') . "n" . $lang->getXxbLang('month') . "j" . $lang->getXxbLang('day'));
		return $tdate;
	}	

	private function getChiDate(){
		$lang = $this->xxbLang;
		
		$y = date("Y");
		for($i = 0; $i < strlen($y); $i++){
			$ty = $this->changeYearNumToChi(substr($y,$i,1));
			$ty2 = $ty2 . $ty;
		}
		
		$m = date("n");
		for($i = 0; $i < strlen($m); $i++){
			$tm = $this->changeMonthDayNumToChi(substr($m,$i,1),strlen($m),$i);
			$tm2 = $tm2 . $tm;
		}

		$d = date("j");
		for($i = 0; $i < strlen($d); $i++){
			$td = $this->changeMonthDayNumToChi(substr($d,$i,1),strlen($d),$i);
			$td2 = $td2 . $td;
		}
		$tdate = $ty2 . $lang->getXxbLang('year') . $tm2 . $lang->getXxbLang('month') . $td2 . $lang->getXxbLang('day');
		return $tdate;
	}

	private function changeMonthDayNumToChi($tmd,$count,$order){
		if($count == 1){
			$temp = $this->changeYearNumToChi($tmd);
		}else{
			if($order == 0){
				$temp = $this->getMDTensToChi($tmd);
			}else{
				$temp = $this->getMDOnesToChi($tmd);
			}
		}
		return $temp;
	}	
	
	private function getMDTensToChi($tmd){
		switch($tmd){
			case 1:
				$ctmd = '十';
				break;
			case 2:
				$ctmd = '二十';
				break;
			case 3:
				$ctmd = '三十';
				break;
			default:
				exit("Wrong Tens number of MonthDay to chinese!");
				break;
		}
		return $ctmd;
	}

	private function getMDOnesToChi($tmd){
		if($tmd == 0){
			$ctmd = '';
		}else{
			$ctmd = $this->changeYearNumToChi($tmd);
		}
		return $ctmd;
	}

	private function changeYearNumToChi($ty){
		switch($ty){
			case 0:
				$cty = '0';
				break;
			case 1:
				$cty = '一';
				break;
			case 2:
				$cty = '二';
				break;
			case 3:
				$cty = '三';
				break;
			case 4:
				$cty = '四';
				break;
			case 5:
				$cty = '五';
				break;
			case 6:
				$cty = '六';
				break;
			case 7:
				$cty = '七';
				break;
			case 8:
				$cty = '八';
				break;
			case 9:
				$cty = '九';
				break;
		}
		return $cty;
	}
}
?>
