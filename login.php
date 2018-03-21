<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/Google-Style-Login.css">
</head>

<body>
      <ul class="warn text-center list-group" style="margin-top: 50px;"></ul>
    <div class="login-card"><img src="assets/img/avatar_2x.png" class="profile-img-card">
        <p class="profile-name-card"> </p>
        <form class="form-signin"><span class="reauth-email"> </span>
            <input class="form-control" type="email" required="" placeholder="Username" autofocus="" id="inputUsername">
            <input class="form-control" type="password" required="" placeholder="Password" id="inputPassword">
            
            <button class="btn btn-primary btn-block btn-lg btn-signin" type="button">Sign in</button>
        </form><a href="forgot-password.php" class="forgot-password">Forgot your password?</a></div>
    <script src="assets/js/jquery.min.js"></script>
   
    <script>
        $(document).ready(function(){
            $(".btn-signin").click(function(event) {
            var user = $("#inputUsername").val();
            var pass = $("#inputPassword").val();
                      $.ajax({
            url: 'api/auth',
            method: 'post',
            data: {username: user,password : pass},
            success: function(ans){ 
              ans = JSON.parse(ans);  
              var date = new Date();
              var date2 = new Date();
              date2.setTime(date.getTime()+(3*24*60*60*1000));
              date.setTime(date.getTime()+(7*24*60*60*1000));
              document.cookie="SNID="+ans.token+"; expires="+date.toGMTString()+"; path=/";
              document.cookie="SNID_=1; expires="+date2.toGMTString()+"; path=/";
              window.location.href = "index.php";
            },
            error:function() {
                 $(".warn").append("<li class='list-group-item text-center text-danger'>Wrong Username Or Password !!</li>");
            }
          });
        });
     });
       
    </script>
</body>
   
</html>