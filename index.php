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
  
    <div class="container" id="full-page">
        <h1 id="timeline_head">Timeline</h1>
        <div class="timelineposts">
             
        </div>
    </div>

      <!-- Modal -->
      <div id="commentModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comments</h4>
            </div>
            <div class="modal-body">
              <p>Some text in the modal.</p>
            </div>
            <div>
              <input id="newComment" class="form-control" type="text" style="float:left !important;width:70%;margin-left: 5px;">
              <input type="hidden" id="postidComment" value="">
              <button id="sendComment" class="btn btn-primary" style="float: left !important;margin-left: 5px;">comment</button>
            </div>   
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>
     
     <?php include 'includes/footer.php'; ?>
    <script type="text/javascript">
       var posts = true;
    </script>
    <script src="assets/js/index.js"></script>
     <script type="text/javascript" src="assets/js/common.js"></script>
    
    
</body>
</html>


