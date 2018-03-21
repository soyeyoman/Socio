<?php


class Comment{
  
  public static function createComment($body,$userid,$post_id){
        global $db;
        $body = htmlspecialchars($body);
        $db->query("INSERT INTO comments (body,user_id,post_id) VALUES (:body,:user_id,:post_id)",array(':body' => $body, ':user_id' => $userid,':post_id' => $post_id));
        return self::commentDetails($post_id,$userid);
  } 
  
  public function displayComments($post_id){
    global $db;
      $comments = $db->query("SELECT comments.body,users.user_name FROM `comments`,users WHERE post_id = :post_id AND users.id = comments.user_id",array(':post_id' => $post_id));
           $ans = '[';
           foreach ($comments as $comment) {
              $ans .= '{"comment":"'.$comment['body'].'",';
              $ans .= '"by":"'.$comment['user_name'].'"},';
           }
           $ans = substr($ans,0,strlen($ans)-1);
           $ans .= ']';
           return $ans;
 }

 private function commentDetails($post_id,$userid){
    global $db;
     $comments = $db->query("SELECT comments.body,users.user_name FROM `comments`,users WHERE post_id = :post_id AND users.id = comments.user_id AND comments.user_id = :user_id ORDER BY comments.id DESC LIMIT 1",array(':post_id' => $post_id,':user_id' => $userid));
           $ans = '[';
           foreach ($comments as $comment) {
              $ans .= '{"comment":"'.$comment['body'].'",';
              $ans .= '"by":"'.$comment['user_name'].'"},';
           }
           $ans = substr($ans,0,strlen($ans)-1);
           $ans .= ']';
           return $ans;

         return $ans;  
 }

}
	
	
