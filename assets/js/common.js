  $(document).ready(function($) {

     //seacr for users in all pages 
   $(".search-input").keyup(function(event) {
      $(".suggest-box").html("");
      var key = $(".search-input").val();
      if(key.trim() != ""){
          $.ajax({
        method : "get",
        url : "api/search?query="+key,
        success : function(ans){
         ans = JSON.parse(ans);

         for(i = 0;i<ans.length;i++){
            $(".suggest-box").append('<li class="list-group-item"><a href="profile.php?profile='+ans[i].user_name+'">'+ans[i].user_name+'</a></li>');
         } 
        },
        error : function(error){
          console.log(error);
        }
      });

      }
   });
  
   //send coments
   $("#sendComment").click(function(event) {
      var body = $("#newComment").val();
      var postid = $("#postidComment").val();
          if(body != ""){

          $.ajax({
          url: 'api/comment',
          method: 'post',
          data: {commentbody:body,postid:$("#postidComment").val()},
          success: function(ans){
             addComment(ans);
          },
          error:function(ans){
              console.log(ans);
          }
        });
          
      }    
    });
    
  });

 //add comment after sendind
function addComment(ans){
   $("#newComment").val("");
  $("#commentModal").modal('show');
    var p = $("#commentModal .modal-dialog .modal-content .modal-body");

    if(ans != "]"){
        ans = JSON.parse(ans); 
        for(i=0;i<ans.length;i++){
              $(p).append("<p>"+ans[i].comment+"  ~"+ans[i].by+"</p><hr>");
        }      
    }

}

 //add comments to modal comment 
 function showComments(ans){
  
  $("#commentModal").modal('show');
    var p = $("#commentModal .modal-dialog .modal-content .modal-body");
    $(p).html("");
     if(ans != "]"){
        ans = JSON.parse(ans); 
        for(i=0;i<ans.length;i++){
              $(p).append("<p>"+ans[i].comment+"  ~"+ans[i].by+"</p><hr>");
        }      
    }
 }         
 
 //cookie
  function getCookie(cname) {
      var name = cname + "=";
      var ca = document.cookie.split(';');
      for(var i=0; i<ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0)==' ') c = c.substring(1);
          if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
      }
      return "";
}



   function getUsername(id){
   if(id == ""){
     url = 'api/username';
   }else{
     url ='api/username?userid='+id ;
   }
 
   var response = $.ajax({
          url: url,
          method: 'get',
          success: function(ans){
            ans = JSON.parse(ans);
            USER = ans.name;
          },
          error:function(ans){
            console.log(ans);
          }  
          });
  
 }