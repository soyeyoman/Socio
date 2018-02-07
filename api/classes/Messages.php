<?php
  
  class Messages{

  	public static function getOneOnOne($userid,$friend_id){
     global $db;
     
     $messages = $db->query("SELECT messages.id, messages.body, s.user_name AS sender , r.user_name AS receiver FROM messages
                 LEFT JOIN users s ON messages.sender = s.id
                 LEFT JOIN users r ON messages.receiver = r.id
                 WHERE (s.id = :friendid AND r.id = :userid) OR (s.id = :userid AND r.id = :friendid) ORDER by messages.id",array(':userid' => $userid,':friendid' => $friend_id));
     return json_encode($messages);
  	}

    public static function sendMessage($sender,$receiver,$body){
      global $db;
      $db->query("INSERT INTO messages (body,sender,receiver) VALUES (:body,:sender,:receiver)",array(':body' => $body,':sender' => $sender,':receiver' => $receiver));
      $id = $db->query("SELECT id FROM messages WHERE sender = :sender AND receiver = :receiver ORDER BY id DESC LIMIT 1",array(':sender' => $sender,':receiver' => $receiver))[0]['id'];
      return $id; 
    }
    
    public static function messageusers($userid){
          global $db;
          $messages = $db->query("SELECT r.id AS rid,s.id AS sid,s.user_name AS sender , r.profile_img AS r_img,s.profile_img  AS s_img, r.user_name AS receiver FROM messages
                 LEFT JOIN users s ON messages.sender = s.id
                 LEFT JOIN users r ON messages.receiver = r.id
                 WHERE s.id = :userid OR r.id = :userid",array(':userid' => $userid));
           
                $user = User::getusername($userid);
                $user = json_decode($user);
                $user = $user->name;
                
                $ans = array();
            
                foreach ($messages as $message) {
                   if(!in_array(array('username' => $message['sender'],'id' => $message['sid'],'img' => $message['s_img']),$ans)){ 
                          $ans[] = array('username' => $message['sender'],'id' => $message['sid'],'img' => $message['s_img']);   
                   }

                   if(!in_array(array('username' => $message['receiver'],'id' => $message['rid'],'img' => $message['r_img']),$ans)){
               
                       $ans[] = array('username' => $message['receiver'],'id' => $message['rid'],'img' => $message['r_img']);
               
                   }

                }
                $final = array();
                foreach ($ans as $an){
                  if($an['username'] != $user){
                    $final[] = array('username' => $an['username'],'id' => $an['id'],'img' => $an['img']);
                  }
                }
          return json_encode($final);
          }
    }
  