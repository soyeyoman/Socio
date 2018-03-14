<?php
    function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}

    if(Login::isloggedin()){
      header("Location:index.php");
   }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Email</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
</head>

<body>
  <div class="container" >
       <ul class="warn text-center list-group" style="margin-top: 50px;"></ul>
      <div class="login-card">
        <p class="profile-name-card">Insert Email of account</p>
        <form class="form-signin"><span class="reauth-email"> </span>
            <input class="form-control" type="email" required="" placeholder="email" auttofocus="true" id="inputemail" required="true">
            
            
            <button class="btn btn-primary btn-block btn-lg btn-cp" type="submit">Change</button>  

          </form>    
    </div>

  </div>  
    
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
       $(document).ready(function(){
          $(".form-signin").on('submit',function(event){
              event.preventDefault();
              var email = $("#inputemail").val();
            
              $.ajax({
                            url : 'api/resetpassword',
                            method: 'post',
                            dataType : "json",
                            data : {
                               email :email
                            },
                            success: function(ans){
                         
                              $(".warn").html("");
                              if(ans.message == "success"){
                                $(".warn").append("<li class='list-group-item text-center text-success'><a href='reset-password.php?token="+ans.token+"'>Reset Password</a></li>");
                              }else{
                                    $(".warn").append("<li class='list-group-item text-center text-danger'>"+ans.message+"</li>");
                              }
                            },
                            error:function(ans){
                                $(".warn").append("<li class='list-group-item text-center text-danger'>Something went wrong</li>");
                            }
                        });
          });
       });
    </script>
  </body>
  </html>