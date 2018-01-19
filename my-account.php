<?php
   function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
     }
   }  

   //redirect if not logged in
   if(!Login::isloggedin()){
     header("Location:login.php");
   }
   $title = "Timeline";

   include 'includes/header.php';
?> 
<div id="full-page">
     <div class="container">
            <div class="container text-center" style="margin-top: 5em;width: 50%">
                <form>
                    <div class="form-group">
                        <label for="username">UserName</label>
                        <input name="username" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="about">About Me</label>
                        <textarea class="form-control" name="about" rows="4" resize="fasle"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="profilepic">Profile Pic</label><div class="clearfix"></div>
                        <img class="img-circle img-responsive" src="prof.jpg">
                        <input type="file" name="profilepic" class="form-control">
                    </div>
                      <div class="form-group">
                          <label for="thumbpic">Profile Pic </label>
                          <img class="img-thumb img-rounded img-responsive" src="prof.jpg" >
                        <input type="file" name="thumbpic" class="form-control">
                    </div>
                    
                </form>
            </div>
        </div>
</div>
  
<?php include 'includes/footer.php'; ?>
  <script type="text/javascript" src="assets/js/common.js"></script>
</body>
</html>
