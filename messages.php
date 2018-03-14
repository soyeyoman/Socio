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
   
   $sender="";  //store if user defined in url
   if(isset($_GET['user'])){
     $sender = $_GET['user'];
   }

   $title = "messages";
   include 'includes/header.php';
   ?>

    
    <div class="container" id="full-page">
        <h1 id="messages_head">My Messages</h1>
        <div class="messages row">
             <div class="col-md-2" >
                 <button class="btn btn-primary" id="addnewmessage" style="margin-bottom: 5px;">New Message</button>
                 <input type="text" id="newmessageuser" class="form-control" placeholder="search user" style="display: none;">
                 <ul class="list-group user-box" style="position: absolute;width: 100% !important;z-index: 20;">
                          
                 </ul>
                 <ul class="list-group list-message-users" style="max-height: 300px;overflow-y: scroll;">
                    
                     
                 </ul>
             </div>
              <div class="col-md-8 col-md-offset-2 live-message">
                  <div class="read-message form-control">
                    <div class="use"></div>   
                  </div>
                  <div class="send-message">
                      <input type="text" class="form-control pull-left" style="max-width: 90% !important;">
                      <button class="btn btn-primary pull-right btn-send-message">SEND</button>
                  </div>
              </div>
        </div>
    </div>

    <?php include 'includes/footer.php';?>
     <script type="text/javascript" src="assets/js/common.js"></script>
    <script type="text/javascript">
        var SENDER = "<?=$sender?>"; // get if url user specified
    </script>
    <script type="text/javascript" src="assets/js/chat.js"></script>
    
</body>

</html>