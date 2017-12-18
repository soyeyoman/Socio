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

   $title = "messages";
   include 'includes/header.php';
   ?>

    
    <div class="container" id="full-page">
        <h1 id="messages_head">My Messages</h1>
        <div class="messages row">
             <div class="col-md-2" style="">
                 <ul class="list-group list-message-users">
                    
                     
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
        var SENDER ;
        var USER = "";
       $(document).ready(function(){
          getUsername("");
          $.ajax({
             url : 'api/messageusers',
             method: 'get',
             success: function(ans){
                     ans = JSON.parse(ans);
                     $.each(ans,function(index) {
                        $(".list-message-users").append(' <li onclick="userClick(this)" user-id="'+ans[index].id+'" class="list-group-item"><img src="'+ans[index].img+'" class="img-circle sm-image"><p>'+ans[index].username+'</p></li>')
                     });
                },
              error: function(ans) {
                   console.log(ans);
                 }  
          });

          $(".btn-send-message").click(function(event) {
             var body = $(".send-message input[type=text]").val();
             if(body.trim()  != ""){
               $.ajax({
                  url : 'api/send-message',
                  method: 'post',
                  data: {body:body,to:SENDER},
                  success: function(ans){
                      ans = JSON.parse(ans);
                      $(".read-message").append('<div class="msg-mine" id="msg'+ans.id+'"">'+body+'</div><div class="clearfix"></div>');
                  }
               }); 
               
             }
          });

      });
        
        function getMessages(){
           $.ajax({
          url: 'api/messages0?friend='+SENDER,
          method: 'get',
          success: function(ans){ 
            getUsername("");
            ans = JSON.parse(ans);
            $.each(ans,function(index) {
              if(ans[index].sender == USER){
                  $(".read-message").append('<div class="msg-mine" id="msg'+ans[index].id+'">'+ans[index].body+'</div><div class="clearfix"></div>');
              }else{
                   $(".read-message").append('<div class="msg-other" id="msg'+ans[index].id+'">'+ans[index].body+'</div><div class="clearfix"></div>');
              }

            });
          
          },
          error: function(ans) {
          
          }
          });
        }
        
        function userClick(obj){
          var id = $(obj).attr('user-id');
          SENDER = id;
          jQuery(".read-message").html("");
          getMessages(id);
        }

    </script>
    
    
</body>

</html>