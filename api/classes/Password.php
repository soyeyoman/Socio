<?php

class Password{

	public static change($oldpass,$newpass,$userid){
		global $db;
        if(password_verify($oldpass,$db->query('SELECT password FROM users WHERE id = :userid',array(':userid' => $userid))[0]['password'])){  
     
              $db->query("UPDATE users SET password = :password WHERE id = :userid",array(':password' => password_hash($newpass,PASSWORD_DEFAULT),':userid' => $userid));
             return '{"message":"success"}';
       
           }else{
             return '{"message" : "Wrong Old password"}';
          }
	}


	public static changeWithKey($token,$newpass){
		global $db;
		  if($db->query("SELECT id FROM password_tokens WHERE token = :token",array(':token' => sha1($_GET['token']) ))){

               $user_id = $db->query("SELECT user_id FROM password_tokens WHERE token = :token",array(':token'=>sha1($_GET['token'])))[0]['user_id'];
             
                $db->query("UPDATE users SET password = :password WHERE id = :userid",array(':password' => password_hash($newpass,PASSWORD_DEFAULT),':userid' => $user_id ));

                $db->query("DELETE FROM password_tokens WHERE token = :token",array(':token' => sha1($_GET['token'])));

                 return '{"message":"success"}';          
       
       }else{
                return '{"message":"INVALID TOKEN !!"}';
       }
	}

}