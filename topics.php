<?php
    function __autoload($class_name){
       if (file_exists("./classes/".$class_name.".php")) {
        require_once("./classes/".$class_name.".php");
  }
}  
   //redirect if not logged in
   if(!Login::isloggedin() ){
   	 header("Location:login.php");
   }elseif (!isset($_GET['topic'])) {
     header("Location:index.php");
   }

   $title = ((isset($_GET['topic']))?$_GET['topic']:'no topic');
      include 'includes/header.php';
?>


<div class="container" id="full-page">
        <h1 id="topic_head">Post matching topic</h1>
        <div class="timelineposts">
             
        </div>
    </div>




<?php include 'includes/footer.php'; ?>
<script type="text/javascript">

         $.ajax({
    url: 'api/topic?topic='+"<?=$_GET['topic']?>",
    method: 'get',
    success: function(ans){ 

      var posts = JSON.parse(ans);
         $.each(posts,function(index) {
          if(posts[index].img == ""){
            $(".timelineposts").append(
                          '<blockquote><p>'+posts[index].body+'</p><footer>'+
                    ' Posted by '+name+' at '+posts[index].date+
                    ' <button class="btn btn-link" post-id="'+posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
                    '<button class="btn btn-link" post-ids="'+posts[index].postid+'" type="button" style="color: #ebf424">comments</button>'+ 
                 '</footer></blockquote>');
          }else{
            $(".timelineposts").append(
                          '<blockquote><p>'+posts[index].body+'</p><footer>'+
                    '<img src="" class="post-img" temp-src="'+posts[index].img+'" id="img'+posts[index].postid+'"> Posted by '+name+' at '+posts[index].date+'<button class="btn btn-link" post-id="'+posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
                    '<button class="btn btn-link" post-ids="'+posts[index].postid+'" type="button" style="color: #ebf424">comments</button>'+ 
                 '</footer></blockquote>');
          }
         });
             
             //add like listener
         $('[post-id]').click(function(){
           var postid = $(this).attr('post-id');
           var btn = $(this);
           $.ajax({
          url: 'api/like',
          method: 'post',
          data: {postid:postid},
          success: function(ans){ 
                      ans = JSON.parse(ans);
                      $(btn).html("<span>❤"+ans.likes+"</span>&nbsp;"+ans.liked);
          },
          error: function(ans) {
            alert(ans);
          }
          });  

       });
           
           //get comments for posts
           $('[post-ids]').click(function(){
          var postid = $(this).attr('post-ids');
          var btn = $(this);
              $.ajax({
                    url: 'api/comments?postid='+postid,
          method: 'get',
          success: function(ans){ 
                      showComments(ans);
          },
          error: function(ans) {
          
          }
          });

         }); 

             //add src to image in post
             $(".post-img").each(function(){
              this.src = $(this).attr('temp-src');
              this.onload = function(){
                this.style.opacity = '1';
             }});
    
    },
    error: function(ans) {
      alert(ans);
    }

  });
   
</script>
</body>

</html>