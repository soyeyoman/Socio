<?php
  function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
     }
   } 

   $db = new DB("127.0.0.1","newsocial","root","");

  if($_SERVER['REQUEST_METHOD'] == "GET"){
     
     if($_GET['url'] == 'comments'){

          $postid = $_GET['postid'];
          echo Comment::displayComments($postid);

     }else if($_GET['url'] == 'username'){
        if(isset($_GET['userid'])){
           $userid = $_GET['userid'];
        }else{
           $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
        }
         
          echo User::getusername($userid);  

     }elseif ($_GET['url'] == 'search') {
           
           echo User::search($_GET['query']);

     }elseif($_GET['url'] == 'messages0'){
       $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
       $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
       $friend_id = $_GET['friend'];

       echo Messages::getOneOnOne($userid,$friend_id);

     }elseif($_GET['url'] == 'messageusers'){
          $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];

          echo Messages::messageusers($userid);
    }elseif($_GET['url'] == 'notifys'){
         $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
         $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
         echo Notify::getNotifications($userid); 
    }elseif ($_GET['url'] == 'topic') {
      $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
         $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
       echo Post::getPostByTopic($_GET['topic'],$userid);
    }elseif($_GET['url'] == 'follow'){
       $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
         $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
       echo Follow::Following($userid,$_GET['profile']);
    }else{
       http_response_code(405);
    }


  }elseif ($_SERVER['REQUEST_METHOD'] == "POST") {

  	 if($_GET['url'] == 'auth'){
          
           $user = $_POST['username'];
           $pass = $_POST['password'];
           
           echo User::login($user,$pass); 

       }else if($_GET['url'] == 'users'){//to add user
         $name = $_POST['username'];
         $password = $_POST['password'];
         $email = $_POST['email'];
         
         echo User::signup($name,$password,$email);

       }else if($_GET['url'] == 'posts'){

        $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
          if($userid == 0){
            http_response_code(409);
            die("no such user");
          } 
          echo Post::displayTimelinePost($userid);

       }else if($_GET['url'] == 'like'){
          $postid = $_POST['postid'];
          $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $likerid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];

          echo Post::like($likerid,$postid);

       }else if($_GET['url'] == 'profile_post'){

          $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $profile_id = $_POST['profile'];
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
          if($userid == 0 || $profile_id == 0){
            http_response_code(409);
            die("no such user");
          } 
          
          echo Post::displayProfilePosts($profile_id,$userid);

       }elseif($_GET['url'] == 'send-message'){
          $receiverid = $_POST['to']; 
          $body = $_POST['body'];
          $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
          
          echo Messages::sendMessage($userid,$receiverid,$body);
       
       }elseif($_GET['url'] == 'createpost'){
        $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];

        if($_FILES['file']['size'] > 0){
          echo  Post::createPostImage($_POST['postbody'],$userid);
        }else{
          echo Post::createPost($_POST['postbody'],$userid);
        }

       }elseif ($_GET['url'] == 'comment') {
          $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
        
         echo Comment::createComment($_POST['commentbody'],$userid,$_POST['postid']);
       }elseif($_GET['url'] == 'changepassword'){
          
          if(isset($_POST['changetoken'])){
             echo Password::changeWithKey($_POST['changetoken'],$_POST['newpass']);
          }else{
              $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_POST['token']);
          $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id'];
            echo Password::change($_POST['oldpass'],$_POST['newpass'],$userid);
          }
       }else{
          http_response_code(405);
       }


  }else if($_SERVER['REQUEST_METHOD'] == 'DELETE'){

      if($_GET['url'] == "auth"){
         $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
        
        echo  User::logout_once($token);
              
      }elseif($_GET['url'] == "authall"){
         $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
         $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id']; 
         echo User::logout_all($userid);
      }elseif ($_GET['url'] == 'follow') {
        $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
         $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id']; 

         echo Follow::Unfollow($userid,$_GET['profile']);
      }


    }elseif($_SERVER['REQUEST_METHOD'] == 'PUT'){
         if($_GET['url'] == 'follow'){
             $token = ((isset($_COOKIE['SNID']))?$_COOKIE['SNID']:$_GET['token']);
             $userid = $db->query("SELECT user_id FROM login_tokens WHERE token = :token",array(':token'=>sha1($token)))[0]['user_id']; 
            echo Follow::Follow_($userid,$_GET['profile']);
         }
    }else{
      	 http_response_code(405);
      }


?>
