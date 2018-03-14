<?php
   function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}
   if(Login::isloggedin()){
   	   $userid =  Login::getUserId();
   }else{
      header("Location:login.php");
   }
   	  if(isset($_GET['profile'])){
         
         if(DB::query("SELECT id FROM users WHERE user_name = :username",array(':username' => $_GET['profile']))){
            
           //get profile id and username from get value  
            $profile_id = DB::query("SELECT id FROM users WHERE user_name = :username",array(':username' => $_GET['profile']))[0]['id'];
            $username = DB::query('SELECT user_name FROM users WHERE id = :userid',array(':userid' =>$profile_id))[0]['user_name'];


         }else{
         	 die('No such user');
         } 

   	  }else{
        //if the profile is not specified goes to the logged users profile
   	   $username = Login::getUserName();
   	   $profile_id = $userid;
   	  }

   
   $title = $username.'\'s profile';
   include 'includes/header.php';
?>
    <div class=".jumbotron picthumb">
       
 
    <img src="images/profile/testman.jpg" class="img-circle profile-img" >
   
  
    </div>
    <div class="container" id="full-page">
        <h1 id="profile_head"><?=$username?>'s Profile</h1>
      
        <div id="profilebtns">
          <div class="followpost"></div>
          <div class="messageprofile"></div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div id="about-me">
                <h3>About Me</h3>
                <p>Welcome to my profile blah blh blah blah blah blah
                blah blah blah blah blah blah blah blah blah</p>
                </div>
                <div id="followdetails">
                  
                </div>
            </div>
          <div class="timelineposts col-md-9"> 
          
          </div>
        </div>
    </div>

      <!-- Modal comments -->
      <div id="commentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comments</h4>
            </div>
            <div class="modal-body">
              <p>Some text in the modal.</p>
            </div>
            <div>
              <input id="newComment" class="form-control" type="text" style="float:left !important;width:70%;margin-left: 5px;">
              <input type="hidden" id="postidComment" value="">
              <button id="sendComment" class="btn btn-primary" style="float: left !important;margin-left: 5px;">comment</button>
            </div>         
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

        <!-- Modal posts -->
      <div id="postModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Create Post</h4>
            </div>
            <div class="modal-body">
                <form id="newpost" action="" method="post" enctype="multipart/form-data">
    <div id="image_preview"><img id="previewing" style="width:20%;height:100px;" src="images/post/noimage.png" /></div>
    <textarea class="form-control" id="postarea" name="postbody" cols="30" rows="10"></textarea><br>
    <hr id="line">
    <div id="selectImage">
    <label>Select Your Image</label><br/>
    <input class="form-control" type="file" name="file" id="file"/>
    <input  class="btn btn-primary" type="submit" value="post" class="submit" />
    <h4 id='loading' >loading..</h4>
<div id="message"></div>
    </div>
    </form>
            </div>
            <div class="modal-footer">

              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

     <?php include 'includes/footer.php'; ?>
    <script type="text/javascript">
       var posts_profile = true;
       var profile = "<?=$profile_id?>";
       var name = "<?=$username?>";
       var user = "<?=$userid?>";
    </script>
    <script src="assets/js/profile.js"></script>
     <script type="text/javascript" src="assets/js/common.js"></script>
    
    
</body>

</html>

