<?php
    function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}

    if(!Login::isloggedin()){
      header("Location:login.php");
   }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
</head>

<body>
    <div class="login-card"><img src="assets/img/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card">Change Password</p>
        <form class="form-signin"><span class="reauth-email"> </span>
            <input class="form-control" type="password" required="" placeholder="Old Password" auttofocus="true" id="inputOldPass">
            <input class="form-control" type="password" required="" placeholder="New Password"  id="inputNewpass">
            <input class="form-control" type="password" required="" placeholder="Confirm Password" id="inputConfirmPassword">
            
            <button class="btn btn-primary btn-block btn-lg btn-cp" type="button">Change</button>    
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
     <script>
         $(document).ready(function($) {
            
       });           
       
     </script>
</body>

</html>