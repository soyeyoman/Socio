<?php
    function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}

    if(Login::isloggedin()){
      header("Location:login.php");
    }
    
    if(!isset($_GET['token'])){
    	header("Location:index.php");
    }

    $token = $_GET['token'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
</head>

<body>
  <div class="container" >
       <ul class="warn text-center list-group" style="margin-top: 50px;"></ul>
      <div class="login-card"><img src="assets/img/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card">Reset Password</p>
        <form class="form-signin"><span class="reauth-email"> </span>
          
            <input class="form-control new" type="password" required="" placeholder="New Password"  id="inputNewpass" required="true">
            <input class="form-control new" type="password" required="" placeholder="Confirm Password" id="inputConfirmPassword" required="true">
            
            <button class="btn btn-primary btn-block btn-lg btn-cp" type="submit">Change</button>  

          </form>    
    </div>

  </div>  
    
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
     <script>
         $(document).ready(function($) {
            $(".form-signin").on('submit',function(event){
                event.preventDefault();
           
                var newpass = $("#inputNewpass").val();
                var confirmpass = $("#inputConfirmPassword").val();
                var error = new Array();
                var arrayindex = 0;
                if(newpass.length < 6){
                    $(".new").css({
                        border: '1px solid red'
                    });
                    error[arrayindex++] = "New password must be more than six characters!!";
                }

                if(newpass != confirmpass){
                    $(".new").css({
                        border: '1px solid red'
                    });
                    error[arrayindex++] = "Passwords do not much !!";
                }
                $(".warn").html("");
                   if(error.length > 0){
                    for(i = 0;i<error.length;i++){
                        $(".warn").append("<li class='list-group-item text-center text-danger'>"+error[i]+"</li>");
                    }
                    }else{
                       
                    $(".new").css({
                        border: ''
                    });
                        $.ajax({
                            url : 'api/changepassword',
                            method: 'post',
                            data : {changetoken:"<?=$token?>",newpass:newpass},
                            success: function(ans){
                              ans = JSON.parse(ans);
                              $(".warn").html("");
                              if(ans.message == "success"){
                                $(".warn").append("<li class='list-group-item text-center text-success'>"+ans.message+"</li>");
                              }else{
                                    $(".warn").append("<li class='list-group-item text-center text-danger'>"+ans.message+"</li>");
                              }
                            },
                            error:function(ans){
                                $(".warn").append("<li class='list-group-item text-center text-danger'>"+ans+"</li>");
                            }
                        });
                
                }



       });           
       });
     </script>
</body>

</html>
