<?php
    function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}
  
  if(!Login::isloggedin()){
  	header("Location:login.php");
  } 
   
   $title = "logout";

   include 'includes/header.php';
?>
  
  <div id="full-page" class="container text-center">
    <div class="container">
           <h1>LOG OUT</h1>
    
        <p>Are you sure you want to logout</p>
          <button class="btn btn-primary" id="onedevice">Only this</button>
        <button class="btn btn-primary" id="alldevives">All Devices</button>
      
    </div>

  </div>
  
  <?php include 'includes/footer.php';?>
      <script src="assets/js/main.js">
      </script>
       <script type="text/javascript" src="assets/js/search.js"></script>
      <script type="text/javascript">
        $(document).ready(function() {
           $("#onedevice").click(function(event) {
              $.ajax({
                 url: 'api/auth',
                 method: 'delete',
                 success: function(ans){
                     window.location = 'login.php';
                     delete_cookie("SNID");
                     delete_cookie("SNID_");
                 },
                 error: function(ans){
                  console.log(ans);
                 }
              });
           });

      
           $("#alldevives").click(function(event) {
              $.ajax({
                 url: 'api/authall',
                 method: 'delete',
                 success: function(ans){
                     window.location = 'login.php';
                      delete_cookie("SNID");
                     delete_cookie("SNID_");
                 },
                 error: function(ans){
                  console.log(ans);
                 }
              });
           });
        });

        var delete_cookie = function(name) {
           document.cookie = name + '=;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        };
      </script>
  </body>
  </html>