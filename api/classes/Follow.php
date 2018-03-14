<?php

class Follow{

	public static function Following($userid,$profile_id){
		 global $db;
           $following = "false";
            if($db->query("SELECT id FROM followers WHERE user_id = :profile_id AND follower_id = :userid",array(':profile_id' => $profile_id,':userid' => $userid) )){
                $following = "true";
            }
            return '{"following":"'.$following.'"}';
	}

	public static function Follow_($userid,$profile_id){
        global $db;
     	$db->query("INSERT INTO followers (user_id,follower_id) VALUES (:profile_id,:userid)",array(':profile_id' => $profile_id,':userid' => $userid));
       return '{"following":"true"}';
	}

	public static function Unfollow($userid,$profile_id){
      global $db;
      $db->query("DELETE FROM followers WHERE user_id = :profile_id AND follower_id = :userid",array(':profile_id' => $profile_id,':userid' => $userid));
        return '{"following":"false"}'; 
   }


}