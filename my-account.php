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
               <ul class="warn text-center list-group" style="margin-top: 50px;"></ul>
                <form class="form" id="account_form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="username">UserName</label>
                        <input name="username" id="username" type="text" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="about">About Me</label>
                        <textarea class="form-control" name="about" id="about" rows="4" resize="fasle"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="profilepic">Profile Pic</label><div class="clearfix"></div>
                        <img id="profilepic_img" class="img-circle img-responsive" src="">
                        <input type="file" name="profilepic" id="profilepic" class="form-control">
                    </div>
                      <div class="form-group">
                          <label for="thumbpic">Profile Pic </label>
                          <img id="jumbpic_img" class="img-thumb img-rounded img-responsive" src="" >
                        <input type="file" name="jumbpic" id="jumbpic" class="form-control">
                    </div>
                    <div class="form-group">
                      <Button id="saveBtn" name="submit" type="submit" class="btn btn-primary btn-lg">SAVE</Button>
                    </div>
                </form>
            </div>
        </div>
</div>
  
<?php include 'includes/footer.php'; ?>
  <script type="text/javascript" src="assets/js/common.js"></script>
  <script type="text/javascript" src="assets/js/myaccount.js"></script>
</body>
</html>
