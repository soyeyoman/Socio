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
          <ul class="warn text-center list-group" style="margin-top: 50px;"></ul>
        <p class="profile-name-card">REGISTER </p>
        <form class="form-signin"><span class="reauth-email"> </span>
            <input class="form-control" type="text" required="" placeholder="Username" auttofocus="true" id="inputUser">
            <input class="form-control" type="email" required="" placeholder="Email address"  id="inputEmail">
            <input class="form-control" type="password" required="" placeholder="Password" id="inputPassword">
            
            <button class="btn btn-primary btn-block btn-lg btn-ca" type="button">Sign Up</button>    
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
     <script>
         $(document).ready(function($) {
             $(".btn-ca").click(function(event) {
            var user = $("#inputUser").val();
            var pass = $("#inputPassword").val();
            var mail = $("#inputEmail").val();
            if(user.trim() == "" || pass.trim() == "" || mail.trim() == ""){
                $(".warn").append("<li class='list-group-item text-center text-danger'>Fill all inputs</li>");
            }else{
                $.ajax({
                url: 'api/users',
                method: 'post',
                data: {username: user,password : pass,email : mail},
                success: function(ans){ 
                   ans = JSON.parse(ans); 
                    $(".warn").html(" ");
                  if(ans.message == "success"){
                    $(".warn").append("<li class='list-group-item text-center text-success'>Account created</li>");
                     $(".warn").append("<li class='list-group-item text-center text-success'><a href='login.php'>Login here</a></li>");
                  }else{
                     $(".warn").append("<li class='list-group-item text-center text-danger'>"+ans.message+"</li>");
                  }
                },
                error:function(ans) {
                    $(".warn").append("<li class='list-group-item text-center text-danger'>Error</li>");
                }
               });
            }
            
        });
     });           
       
     </script>
</body>

</html>