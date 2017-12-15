<?php
class Post{
	
	public static function createPost($body,$userid){
        global $db;
        Notify::createNotify($userid,$body);
        $topics = self::getTopics($body); 
        $body = self::addlink($body); //for profile mentions
        
        $db->query("INSERT INTO posts (body,user_id,topics) VALUES (:body,:user_id,:topics)",array(':body' => $body, ':user_id' => $userid,':topics' => $topics));
        $post = $db->query("SELECT * FROM posts WHERE user_id =:userid ORDER BY id DESC LIMIT 1",array(':userid' => $userid));

        return json_encode($post);
	}

  public function createPostImage($body,$userid){
     global $db;
     self::createPost($body,$userid); 
     $id = $db->query("SELECT id FROM posts WHERE user_id = :userid  ORDER BY id DESC LIMIT 1",array(':userid' => $userid))[0]['id'];
     
     $path = '../images/post/'.basename($_FILES["file"]["name"]);
     move_uploaded_file($_FILES["file"]["tmp_name"], $path);
     $db->query("UPDATE posts SET post_img = :img WHERE id = :id",array(':id' => $id,':img'=>'images/post/'.basename($_FILES['file']['name'])));
     $post = $db->query("SELECT * FROM posts WHERE user_id =:userid ORDER BY id DESC LIMIT 1",array(':userid' => $userid));
     return json_encode($post);
  }

	public static function like($likerid,$postid){
    global $db;
      if(!$db->query("SELECT * FROM post_likes WHERE liker_id = :user AND post_id = :post",array(':user' => $likerid,':post' =>$postid))){
       
            $db->query("UPDATE posts SET likes = likes+1 WHERE id = :postid ",array(':postid' => $postid));
            $db->query("INSERT INTO post_likes (post_id,liker_id) VALUES (:post_id,:liker_id)",array(':post_id' => $postid,':liker_id' => $likerid));
            $liked = "unlike";
            //Notify::createNotify($likerid,"",$postid);
         }else{
             
             $db->query("UPDATE posts SET likes = likes-1 WHERE id = :postid",array(':postid' => $postid));
             $db->query("DELETE FROM post_likes WHERE post_id = :post_id AND liker_id = :liker_id",array(':post_id' => $postid,':liker_id' => $likerid));
             $liked = "like";
         }  
         
         echo '{ "likes":';
         echo $likes = $db->query("SELECT likes FROM posts WHERE id = :postid",array(':postid' => $postid))[0]['likes'];
         echo ',"liked" :"'.$liked.'"';
         echo '}'; 
	}



	public static function displayProfilePosts($profile_id,$userid){
    global $db;
      $postsray = $db->query("SELECT * FROM posts WHERE user_id = :user_id  ORDER BY posted_at DESC",array(':user_id' => $profile_id));

             $posts = '[';
          foreach($postsray as $post){

          //check if is liked
         $liked = 'like';
         if($db->query("SELECT * FROM post_likes WHERE liker_id = :user AND post_id = :post",array(':user' => $userid,':post' =>  $post['id']))){
               $liked = 'unlike';
          }

          $posts .= '{"body" :"'.$post['body'].'",';
          $posts .= '"postid":"'.$post['id'].'",';
          $posts .= '"img":"'.$post['post_img'].'",';
          $posts.=  '"likes":"'.$post['likes'].'",';
          $posts .=  '"liked":"'.$liked.'",';
          $posts .=  '"date":"'.$post['posted_at'].'"},';
            
         }   

         $posts = substr($posts,0,strlen($posts)-1);
         $posts .= ']';
         echo $posts;
   }
  


 public static function displayTimelinePost($userid){
 	       global $db;
          //gets post of pple user follows  
         $postsbyfollowing = $db->query("SELECT posts.body,users.user_name AS name, posts.post_img ,posts.id AS id,posts.likes,posts.posted_at AS _date,posts.user_id AS poster FROM posts,followers,users WHERE 
           followers.user_id = posts.user_id
            AND users.id = posts.user_id
           AND (followers.follower_id = :user_id OR posts.user_id = :user_id) ORDER BY posted_at DESC",array(':user_id' => $userid));
           
           $posts = '[';
          foreach ($postsbyfollowing as $post) {

          //check if is liked
         $liked = 'like';
         if($db->query("SELECT * FROM post_likes WHERE liker_id = :user AND post_id = :post",array(':user' => $userid,':post' =>  $post['id']))){
               $liked = 'unlike';
          }

          $posts .= '{"body" :"'.$post['body'].'",';
          $posts .= '"postid":"'.$post['id'].'",';
          $posts .= '"img":"'.$post['post_img'].'",';
          $posts .= '"by":"'.$post['name'].'",';
          $posts.=  '"likes":"'.$post['likes'].'",';
          $posts .=  '"liked":"'.$liked.'",';
          $posts .=  '"date":"'.$post['_date'].'"},';
            
         }   

         $posts = substr($posts,0,strlen($posts)-1);
         $posts .= ']';
         echo $posts;
 }


 
 private static function checkliked($userid,$post_id){
     	//check if is liked
	     $liked = 'like';
	     if(DB::query("SELECT * FROM post_likes WHERE liker_id = :user AND post_id = :post",array(':user' => $userid,':post' =>  $post_id))){
	           $liked = 'unlike';
	      }
	      return $liked;
	 }
   
   //delete posts 
   public static function delete($postid,$userid){
     if(DB::query("SELECT id FROM posts WHERE id = :post_id AND user_id = :userid",array(':post_id' => $postid,':userid' => $userid))){
        DB::query("DELETE FROM post_likes WHERE post_id = :post_id",array(':post_id' => $postid));
        DB::query("DELETE FROM posts WHERE id = :post_id",array(':post_id' => $postid));

        return "Post deleted";
     }else{
       return "Not deleted";
     }
   }
   
   //adds links for @mentions and # tags
   private static function addlink($body){
      $text = explode(" ",$body);
      $newstring = "";
      foreach ($text as $word) {
         if(substr($word,0,1) == "@"){
             $newstring .= " <a href='profile.php?profile=".substr($word,1)."'>".htmlspecialchars($word)."</a> ";
         }elseif(substr($word,0,1) == '#'){
              $newstring .= " <a href='topics.php?topic=".substr($word,1)."'>".htmlspecialchars($word)."</a> "; 
         }else{
             $newstring .= htmlspecialchars($word)." ";
         }
      }
      return $newstring ;
   }
  
  public function getPostByTopic($topic,$userid){
      global $db;
       if($db->query("SELECT * FROM posts WHERE FIND_IN_SET(:topic,topics)",array(':topic' => $topic))){
          $postsray = $db->query("SELECT * FROM posts WHERE FIND_IN_SET(:topic,topics)",array(':topic' => $topic));
                 $posts = '[';
          foreach($postsray as $post){

          //check if is liked
         $liked = 'like';
         if($db->query("SELECT * FROM post_likes WHERE liker_id = :user AND post_id = :post",array(':user' => $userid,':post' =>  $post['id']))){
               $liked = 'unlike';
          }

          $posts .= '{"body" :"'.$post['body'].'",';
          $posts .= '"postid":"'.$post['id'].'",';
          $posts .= '"img":"'.$post['post_img'].'",';
          $posts.=  '"likes":"'.$post['likes'].'",';
          $posts .=  '"liked":"'.$liked.'",';
          $posts .=  '"date":"'.$post['posted_at'].'"},';
            
         }   

         $posts = substr($posts,0,strlen($posts)-1);
         $posts .= ']';
         echo $posts;
      }else{
         echo 'no';
      }
  } 
   //get topics from #tags
 private  static function getTopics($body){
       $text = explode(" ",$body);
       $topics = "";
      foreach ($text as $word) {
         if(substr($word,0,1) == "#"){
             $topics .= substr($word,1).",";
         }
      }
      return  $topics;
   }

   

}