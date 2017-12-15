
<?php
   require 'classes/Login.php';
   require 'classes/DB.php';

   //redirect if not logged in
   if(!Login::isloggedin()){
   	 header("Location:login.php");
   }
   $title = "Notifications";
   include 'includes/header.php';    
?>
 
    <div class="container" id="full-page">
        <h1 id="noti_head">Notifications</h1>
        <div class="notifications row">
             <ul class="list-group noti-list">
              
              
             </ul>
          </div>
        </div>
    </div>
    <?php include 'includes/footer.php';?>
    <script src="assets/js/main.js">
    </script>
    <script type="text/javascript">
       $(document).ready(function(){
           $.ajax({
               url : 'api/notifys',
               method: 'get',
               success: function(ans){
                    ans = JSON.parse(ans);

                    $.each(ans,function(index) {
                      if(ans[index].type == 1){

                       $(".noti-list").append('<li class="list-group-item">'+ans[index].user_name+' liked your post ..<span>'+ans[index].extra+'</span></li>');
                      }else if(ans[index].type == 2){
                        $(".noti-list").append('<li class="list-group-item">'+ans[index].user_name+' mentioned you in a post ..<span>'+ans[index].extra+'</span></li>');
                      }
                    });

               },
               error: function(ans){
                console.log(ans);
               }

            });
       });
    </script>