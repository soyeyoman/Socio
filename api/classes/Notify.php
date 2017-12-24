<?php

  
  class Notify
  {
  	
  	//notify user if @mentioned
   public static function createNotify($senderid,$body = "",$postid = ""){
     global $db;
	    if($body == "" && $postid != ""){
         
          $post = $db->query("SELECT user_id,body FROM posts WHERE id = :postid",array(':postid' => $postid));
          $receiver = $post[0]['user_id'];
          $body = $post[0]['body']; 
          if($receiver != 0){
          	 $db->query("INSERT INTO notifications (type,receiver,sender,extra) VALUES (:type,:receiver,:sender,:extra)",array(':type' => 2,':receiver' => $receiver,':sender' => $senderid,':extra' => '{"postid":'.$postid.',"body": '.substr($body,0,(strlen($body)/2)).'}'));
          }
	    }else{
	      $text = explode(" ",$body);
	      foreach ($text as $word) {
	         if(substr($word,0,1) == "@"){
	            
	            $receiverid = $db->query("SELECT id FROM users WHERE user_name = :username",array(':username' => substr($word,1)))[0]['id'];
              $postid = $db->query("SELECT id FROM posts WHERE user_id = :user_id ORDER BY id DESC LIMIT 1",array(':user_id' => $senderid))[0]['id'];
	            if($receiverid != 0){
	               $db->query("INSERT INTO notifications (type,receiver,sender,extra) VALUES (:type,:receiver,:sender,:extra)",array(':type' => 1,':receiver' => $receiverid,':sender' => $senderid,':extra' => '{"postid":'.$postid.',"body": '.substr($body,0,(strlen($body)/2)).'}'));
	           }
	         }
	      }
	    }       
	     
   }

    public static function getNotifications($userid){
       global $db;
    	  
        $notifications = $db->query("SELECT notifications.type,users.user_name,notifications.extra FROM notifications,users WHERE receiver = :userid AND sender = users.id ORDER BY notifications.id DESC",array(':userid' =>$userid));

        return json_encode($notifications);

    } 

  }