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
  

</div>
  
<?php include 'includes/footer.php'; ?>
  <script type="text/javascript" src="assets/js/common.js"></script>
</body>
</html>
