<?php
  include "classes/DB.php";
  if(isset($_POST['log'])){
  	 $password = $_POST['password'];
  	 $username = $_POST['username'];

  	 if(DB::query('SELECT user_name FROM users WHERE user_name = :username',array(':username' => $username))){
  	 	if(password_verify($password,DB::query('SELECT password FROM users WHERE user_name = :username',array(':username' => $username))[0]['password'])){
             
             $cstring = True;
             $token = bin2hex((openssl_random_pseudo_bytes(64,$cstring)));
             $user_id = DB::query("SELECT id FROM users WHERE user_name = :username",array(':username' => $username))[0]['id'];
             
             DB::query("INSERT INTO login_tokens (token,user_id) VALUES (:token,:user_id)",array(':token'=> sha1($token),':user_id' => $user_id));	

             setcookie("SNID",$token,time() + 60 * 60 * 24 * 7,"/",null,null,false);
             setcookie("SNID_","1",time() + 60 * 60 * 24 * 3,"/",null,null,true);
             echo "Logged in";  
  	 	} else{
  	 		echo "Wrong Password";
  	 	}
  	 }else{
  	 	echo "Hmm I don't know you !!";
  	 }
  }
?>

<form method="post" action="login.php">
	<h1>LOG IN</h1>
	<input type="text" name="username" placeholder="username"><br><br>
	<input type="password" name="password" placeholder="password"><br><br>
	<button type="submit" name="log">Log In</button>
</form>