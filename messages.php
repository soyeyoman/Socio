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
   
   $sender="";
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
        var SENDER = "<?=$sender?>";
        var USER = "";
       $(document).ready(function(){
         
          $.ajax({
             url : 'api/messageusers',
             method: 'get',
             success: function(ans){
                     ans = JSON.parse(ans);
                     $.each(ans,function(index) {
                        $(".list-message-users").append(' <li onclick="userClick(this)" user-id="'+ans[index].id+'" class="list-group-item"><img src="'+ans[index].img+'" class="img-circle sm-image"><p>'+ans[index].username+'</p></li>')
                     });
                       if(SENDER != ""){
                         getMessages();
                         $.ajax({
                           url: 'api/username?userid='+SENDER,
                           method: 'get',
                           success: function(ans){
                             ans = JSON.parse(ans).name;
                               var th = $(".list-message-users").find("[user-id = "+SENDER+"]");
                               if($(th).html() != undefined){
                                    $(th).remove();
                                     $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="images/profile/default.png" class="img-circle sm-image"><p>'+ans+'</p></li>');
                                    $("[user-id = "+SENDER+"]").css({
                                      background : "lightblue"
                                    });
                               }else{
                                  $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="images/profile/default.png" class="img-circle sm-image"><p>'+ans+'</p></li>');
                                    $("[user-id = "+SENDER+"]").css({
                                      background : "lightblue"
                                    });
                               }
                           },
                           error:function(ans){
                                console.log(ans);
                           }  
                        });
                        
                       }
                       getUsername("");
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
                      $(".send-message input[type=text]").val("");
                  }
               }); 
               
             }
          });
         
      
         $("#newmessageuser").keyup(function(event) {
            $(".user-box").html("");
            var key = $("#newmessageuser").val();
            if(key.trim() != ""){
                $.ajax({
              method : "get",
              url : "api/search?query="+key,
              success : function(ans){
               ans = JSON.parse(ans);
               for(i = 0;i<ans.length;i++){
                 if(ans[i].user_name != USER){
                    $(".user-box").append('<li class="list-group-item newmessageselect" user-id="'+ans[i].id+'" style="cursor:pointer;">'+ans[i].user_name+'</li>');
                 }
            
               } 

                 $(".newmessageselect").click(function(event) {
                    var id = $(this).attr('user-id');
                    var name = $(this).html();
                    SENDER = id;
                    $("#newmessageuser").val("");
                    $(".user-box").html("");
                    $("#newmessageuser").slideUp();
                     $(".list-message-users li").each(function(index, el) {
                          $(this).css({
                           background : "white"
                           });
                     });
                    $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="images/profile/default.png" class="img-circle sm-image"><p>'+name+'</p></li>');
                     $("[user-id = "+id+"]").css({
                         background : "lightblue"
                      });
                 });
              },
              error : function(error){
                console.log(error);
              }
            });

            }
        });

         $("#addnewmessage").click(function(){
            $("#newmessageuser").slideDown();
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
          $(".list-message-users li").each(function(index, el) {
             $(this).css({
               background : "white"
             });
          });
          var id = $(obj).attr('user-id');
          var th = $(".list-message-users").find("[user-id = "+id+"]");
          var name = $(th).find("p").html();
          $(th).remove();
           $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+id+'" class="list-group-item"><img src="images/profile/default.png" class="img-circle sm-image"><p>'+name+'</p></li>');
          $("[user-id = "+id+"]").css({
            background : "lightblue"
          });
          SENDER = id;
          jQuery(".read-message").html("");
          getMessages(id);
        }
     
    </script>
    
    
</body>

</html>