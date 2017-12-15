<?php

class Password{

	public static function change($oldpass,$newpass,$userid){
		global $db;
        if(password_verify($oldpass,$db->query('SELECT password FROM users WHERE id = :userid',array(':userid' => $userid))[0]['password'])){  
     
              $db->query("UPDATE users SET password = :password WHERE id = :userid",array(':password' => password_hash($newpass,PASSWORD_DEFAULT),':userid' => $userid));
             return '{"message":"success"}';
       
           }else{
             return '{"message" : "Wrong Old password"}';
          }
	}


	public static function changeWithKey($token,$newpass){
		global $db;
		  if($db->query("SELECT id FROM password_tokens WHERE token = :token",array(':token' => sha1($token) ))){

               $user_id = $db->query("SELECT user_id FROM password_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
             
                $db->query("UPDATE users SET password = :password WHERE id = :userid",array(':password' => password_hash($newpass,PASSWORD_DEFAULT),':userid' => $user_id ));

                $db->query("DELETE FROM password_tokens WHERE token = :token",array(':token' => sha1($token)));

                 return '{"message":"success"}';          
       
       }else{
                return '{"message":"INVALID TOKEN !!"}';
       }
	}


  public static function reset($email){
    global $db;
       if($db->query('SELECT id FROM users WHERE email = :email',array(':email' => $email))){
             $cstring = True;
             $token = bin2hex((openssl_random_pseudo_bytes(64,$cstring)));
             $user_id = $db->query("SELECT id FROM users WHERE email= :email",array(':email' => $email))[0]['id'];
             
             $db->query("INSERT INTO password_tokens (token,user_id) VALUES (:token,:user_id)",array(':token'=> sha1($token),':user_id' => $user_id)); 

            echo '{"message":"success","token":"'.$token.'"}';
      }else{
         echo '{"message":"No such Email!!"}';
      }
  }

}