<?php

                /***************************************************************************
		                                session.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: cookie.php,v 1.4 2006/04/18 17:23:09 brgd Exp $
		
		 ***************************************************************************/	 
class cookie{
	private static $num = 0;
	
	//Set variables
	private $globalConfig;
	
	private $userinfo = array();

	//Constructor
	private function cookie($obj){
		$this->globalConfig  = $obj;
		$this->checkUser();
		$this->optimizeTables();
	}
	
	public static function makeObj($obj){
		if(cookie::$num == 0){
			cookie::$num++;
			return new  cookie($obj);
		}else{
			exit('Just can make only one session pbject!');
		}
	}
	
	public function getUserinfo($name){
		return $this->userinfo[$name];
	}
	
	public function getAllUserinfo(){
		return $this->userinfo;
	}
	
	public function decode_Ip($int_ip)
	{
		$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));
		return hexdec($hexipbang[0]). '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
	}
	
	private function checkUser(){
		$tempcookie = $this->globalConfig->getCookie($this->globalConfig->getXxbCookie('name'));
		if(($tempcookie == null) || (!$this->ifCookieExistInDb($tempcookie))){ //cookie not exist in browser
			$this->beginCookie();
			return;
		}else{  //cookie exist in browser and exist in db
			if($this->globalConfig->getGet('target') == 2){ //login or logout
				if($this->userinfo['userid'] == 0){ //check guest,may to login
					if(($this->globalConfig->getPost('userid') == null) || ($this->globalConfig->getPost('userpass') == null)){
						$this->upCookieStartTime($tempcookie);
						return;
					}else{ //have user name and pass
						$this->userLogin($tempcookie,$this->globalConfig->getPost('userid'),$this->globalConfig->getPost('userpass')); //guest login  and set $this->userExactType
					}
				}else{ //logout
					if(($this->globalConfig->getPost('userid') == null) && ($this->globalConfig->getPost('userpass') == null)){
						$this->userLogout($tempcookie); //guest logout  and set $this->userExactType
					}
				}
			}
			$this->upCookieStartTime($tempcookie);
		}
	}
	
	private function beginCookie(){
		$ip = $this->getIp();
		$browser = $_SERVER['HTTP_USER_AGENT'];
		$cookieid = md5(uniqid($ip . $this->currenttime));
		setcookie($this->globalConfig->getXxbCookie('name'), $cookieid, 0, $this->globalConfig->getXxbCookie('path'), $this->globalConfig->getXxbCookie('domain'), $this->globalConfig->getXxbCookie('secure'));
		
		$sql = "INSERT INTO " . $this->globalConfig->getSqlTable('cookies') . " (userid, cookieval, usertype, cookie_start,browser,ip) 
				VALUES (0,'" . $cookieid . "','G'," . $this->globalConfig->getSystemTime() . ",'" . $browser . "','" . $ip . "')";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {        
			die ($result->getMessage() . " --> cookie.php 85");
		}
		$this->userinfo['userid'] = 0;
	}
	
	private function ifCookieExistInDb($co){
		//delete expired session
		$expiry_time = ($this->globalConfig->getSystemTime() - $this->globalConfig->getXxbCookie('length'));
		$sql = "DELETE FROM " . $this->globalConfig->getSqlTable('cookies') . " 
			WHERE cookie_start < $expiry_time";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {        
			die ($result->getMessage() . " --> cookie.php 97");
		}
		
		$sql = "SELECT * FROM " . $this->globalConfig->getSqlTable('cookies') . " WHERE cookieval = '$co'";
		$result1 = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result1)) {        
			die ($result1->getMessage());
		}
		
		$tempnum1 = $result1->numRows();
		if($tempnum1 == 0)	{
			return false;
		}
		
		$tempval1 = $result1->fetchRow();
		
		if($tempnum1 == 1)	{
			if(($tempval1['browser'] != $_SERVER['HTTP_USER_AGENT']) ||
				 ($tempval1['ip'] != $this->getIp())){
				return false;
			}
			
			$id = $tempval1['userid'];
			if($id == 0){
				$this->userinfo['userid'] =  0;
			}else{ //get all user and user's bureau information
				$sql = "SELECT us.*, bu.name AS bname, bu.fullname AS bfullname, bu.address AS baddress, bu.zip AS bzip, bu.www AS bwww FROM " . 
						$this->globalConfig->getSqlTable('user') . " AS us, " . 
						$this->globalConfig->getSqlTable('bureau') . " AS bu " . 
						" WHERE us.userid = $id " . 
						" AND us.bureauid = bu.bureauid";
				$result2 = $this->globalConfig->getXxbDatabase()->query($sql);
				if (DB::isError($result2)) {        
					die ($result2->getMessage() . " --> cookie.php 130");
				}
				$tempval2 = $result2->fetchRow();
				
				$this->userinfo = $tempval2;
			}
			
			return true;
		}
	}
	
	private function userLogin($co,$id,$pass){
		if(!eregi("^[1-9]{1}[0-9]*$", $id)){
			$this->globalConfig->redirector("?target=2&fail=1");
		}
		
		$tempip = $this->getIp();
		$sql = "SELECT * FROM " . $this->globalConfig->getSqlTable('user') . " WHERE userid = $id";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {        
			die ($result->getMessage() . " --> cookie.php 150");
		}
		$tempnum = $result->numRows();
		$tempval = $result->fetchRow();
		
		if($tempnum == 1)	{
			if($tempval['user_pass'] == $pass){
				//ok,will update in cookie table of DB
				$temptype=$tempval['usertype'];
				$sql =  "UPDATE " . $this->globalConfig->getSqlTable('cookies') . "
					SET userid = $id,cookie_start = '" . $this->globalConfig->getSystemTime() . "',usertype = '$temptype'
					WHERE cookieval  = '$co'";
				$result1 = $this->globalConfig->getXxbDatabase()->query($sql);
				if (DB::isError($result1)) {        
					die ($result1->getMessage() . " --> cookie.php 164");
				}
				
				//update user log information in DB
				$sql =  "INSERT INTO " . $this->globalConfig->getSqlTable('loginfo') . "
					 (userid,ip,time,sysinfo) Values ($id,'$tempip','" . $this->globalConfig->getSystemTime() . "','"
					 .  $_SERVER['HTTP_USER_AGENT'] . "')";
				$result3 = $this->globalConfig->getXxbDatabase()->query($sql);
				if (DB::isError($result3)) {        
					die ($result3->getMessage() . " --> cookie.php 173");
				}
				$this->globalConfig->redirector();
			}
		}
		$this->globalConfig->redirector("index.php?target=2&fail=1");
	}
	
	private function userLogout($co){
		$sql =  "UPDATE " . $this->globalConfig->getSqlTable('cookies') . "
					SET userid = 0,cookie_start = '" . $this->globalConfig->getSystemTime() . "',usertype = 'G'	WHERE cookieval  = '$co'";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {        
			die ($result->getMessage() . " --> cookie.php 186");
		}
		$this->globalConfig->redirector();		
	}
	
	private function upCookieStartTime($co){
		$sql =  "UPDATE " . $this->globalConfig->getSqlTable('cookies') . "
					SET cookie_start = '" . $this->globalConfig->getSystemTime() . "' WHERE cookieval  = '$co'";
		$result = $this->globalConfig->getXxbDatabase()->query($sql);
		if (DB::isError($result)) {        
			die ($result->getMessage() . " --> cookie.php 196");
		}
	}
	
	private function getIp()
	{
		$client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
		return  $this->encode_Ip($client_ip);
	}
	
	private function encode_Ip($dotquad_ip)
	{
		$ip_sep = explode('.', $dotquad_ip);
		return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
	}
	
	private function optimizeTables()
	{/**
		$sql = "SELECT * FROM " . $this->nmxhwLang['operation_table'];
		if ( !($result = $this->nmxhwSqlObj->query($sql)) )
		{
			die('Error ocurs while selecting opration table.[common.php]');
		}
		$tempnum = $this->nmxhwSqlObj->sql_Numrows($result);
		if($tempnum == 0)
		{
			$sql = "INSERT INTO " . $this->nmxhwLang['operation_table'] . " (optisestime, optialltime) 
				VALUES ($this->currentTime,$this->currentTime)";
			if ( !($result = $this->nmxhwSqlObj->query($sql)) )
			{
				die('Error occurs while insert operation table.[common.php]');
			}
			$sql = "SELECT * FROM " . $this->nmxhwLang['opration_table'];
			if ( !($result = $this->nmxhwSqlObj->query($sql)) )
			{
				die('Error ocurs while selecting opration table.[common.php]');
			}
		}
		while($temp1[] = $this->nmxhwSqlObj->sql_Fetchrow($result));
		if(($this->currentTime - $temp1[0]['optisestime']) > 15000)
		{
			$sql =  "OPTIMIZE TABLE " . $this->nmxhwLang['operation_table'];
			if ( !$this->nmxhwSqlObj->query($sql) )
			{
				die('Error ocurs while changing opration table record.[common.php  382]');
			}
			
			$sql =  "UPDATE " . $this->nmxhwLang['operation_table'] . 
				" SET optisestime = " . $this->currentTime . 
				" WHERE optisestime  = " . $temp1[0]['optisestime'];
			if ( !$this->nmxhwSqlObj->query($sql) )
			{
				die('Error ocurs while changing operation table record.[common.php 391]');
			}
		}
		
		if(($this->currentTime - $temp1[0]['optialltime']) > 86400)
		{
			$sql =  "OPTIMIZE TABLE " . $this->nmxhwLang['operation_table'] . "," .
				$this->nmxhwLang['fullvar_table'] . "," .
				$this->nmxhwLang['admin_table'] . "," .
				$this->nmxhwLang['user_table'] . "," .
				$this->nmxhwLang['adminvote_table'] . "," .
				$this->nmxhwLang['guestvot_table'];
			if ( !$this->nmxhwSqlObj->query($sql) )
			{
				die('Error ocurs while changing opration table record.[common.php  404]');
			}
			
			$sql =  "UPDATE " . $this->nmxhwLang['operation_table'] . 
				" SET optialltime = " . $this->currentTime . 
				" WHERE optialltime  = " . $temp1[0]['optialltime'];
		
			if ( !$this->nmxhwSqlObj->query($sql) )
			{
				die('Error ocurs while changing opration table record.[common.php  413]');
			}
		}
		**/
	}
}
?>
