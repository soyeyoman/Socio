<?php
class Login{
   private static $userid;
   private static $username;
   public function isloggedin(){

       if(isset($_COOKIE["SNID"])){
            	  if(DB::query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token' => sha1($_COOKIE['SNID'])))){
       	  	    self::$userid = DB::query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token' => sha1($_COOKIE['SNID'])))[0]['user_id'];

                self::$username = DB::query('SELECT user_name FROM users WHERE id = :userid',array(':userid'=> self::$userid))[0]['user_name'];

       	  	 if(!isset($_COOKIE['SNID_'])){
       	  	 	DB::query("DELETE FROM login_tokens WHERE token = :token",array(':token' => sha1($_COOKIE['SNID'])));

	             $cstring = True;
	             $token = bin2hex((openssl_random_pseudo_bytes(64,$cstring)));	

	             DB::query("INSERT INTO login_tokens (token,user_id) VALUES (:token,:user_id)",array(':token'=> sha1($token),':user_id' => self::$userid));	

                 setcookie("SNID",$token,time() + 60 * 60 * 24 * 7,"/",null,null,true);
                 setcookie("SNID_","1",time() + 60 * 60 * 24 * 3,"/",null,null,true);
       	  	 }
       	  	 return true;
       	  }
       }

       return false;
  }

  public function getUserId(){
  	return self::$userid;
  }	

  public function getUserName(){
    return self::$username;
  }
  
}