
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
     <script type="text/javascript" src="assets/js/common.js"></script>
    <script type="text/javascript">
       $(document).ready(function(){
           $.ajax({
               url : 'api/notifys',
               method: 'get',
               success: function(ans){
                    ans = JSON.parse(ans);
                    
                    $.each(ans,function(index) {
                      var extra = JSON.parse(ans[index].extra);
                      if(ans[index].type == 2){

                       $(".noti-list").append('<li class="list-group-item" postid="'+extra.postid+'" >'+ans[index].user_name+' liked your post </li>');
                      }else if(ans[index].type == 1){
                        $(".noti-list").append('<li class="list-group-item" postid="'+extra.postid+'">'+ans[index].user_name+' mentioned you in a post ..</li>');
                      }
                    });
                    notiaction();
               },
               error: function(ans){
                console.log(ans);
               }

            });
       });

       function notiaction(){
            $('[postid]').click(function(){
                location.href = 'profile.php?#'+$(this).attr('postid');
            });
       }

    
    </script>