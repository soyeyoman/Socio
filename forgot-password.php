<?php
   include 'classes/DB.php';
   
   if(isset($_POST['submit'])){
      $email = $_POST['email'];
      if(DB::query('SELECT id FROM users WHERE email = :email',array(':email' => $email))){
             $cstring = True;
             $token = bin2hex((openssl_random_pseudo_bytes(64,$cstring)));
             $user_id = DB::query("SELECT id FROM users WHERE email= :email",array(':email' => $email))[0]['id'];
             
             DB::query("INSERT INTO password_tokens (token,user_id) VALUES (:token,:user_id)",array(':token'=> sha1($token),':user_id' => $user_id));	

             echo $token;
      }else{
      	 echo "No such Email";
      }
   }

?>
<h1>Enter Email to reset password</h1>
<form action="forgot-password.php" method="post">
	<input type="email" name="email" placeholder="email"><br><br> 
	<input type="submit" name="submit" value="Send Email">
</form>