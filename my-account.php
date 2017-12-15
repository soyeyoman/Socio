
<?php

 function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}
if(!Login::isloggedin()){
     header("Location:login.php");
   }
   
  if(isset($_POST['profimg'])){
    $username = Login::getUserName();
    $userid = Login::getUserId();
  	
    Image::ImageUpload($username,"profileimg","profile",$userid);
  }
?>
<h1>My Account</h1>
<form method="post" action="my-account.php" enctype="multipart/form-data">
   <input type="file" name="profileimg">
   <input type="submit" name="profimg">
</form>