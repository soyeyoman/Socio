$(document).ready(function(){
	
  if(posts_profile){

           $.ajax({
		url: 'api/profile_post',
		method: 'post',
		data: {token:"test",profile:profile},
		success: function(ans){	
         if(ans != "]"){
         			  var posts = JSON.parse(ans);
		     $.each(posts,function(index) {
		     	if(posts[index].img == ""){
		     		$(".timelineposts").append(
                          '<blockquote id="post'+posts[index].postid+'"><p>'+posts[index].body+'</p><footer>'+
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
       
           $('[post-ids]').click(function(){
		      var postid = $(this).attr('post-ids');
		      var btn = $(this);
              $.ajax({
                    url: 'api/comments?postid='+postid,
					method: 'get',
					success: function(ans){ 
                       $("#postidComment").val(postid);
                      showComments(ans);
					},
					error: function(ans) {
					
					}
		     	});

		     }); 

               $(".post-img").each(function(){
		          this.src = $(this).attr('temp-src');
		          this.onload = function(){
		            this.style.opacity = '1';
             }});
         }

		
	  },
	  error: function(ans) {
			alert(ans);
		}
	});

 if(user != profile){
     //get following details
     $.ajax({
      url:'api/follow?profile='+profile,
      method: 'get',
      success:function(ans){
         ans = JSON.parse(ans);
         if(ans.following == "true"){
           $(".followpost").html('<button class="btn btn-primary unfollow">Unfollow</button>');
         }else{
            $(".followpost").html('<button class="btn btn-primary follow">follow</button>');
         }

         followListenserAdd();
      },
      error:function(ans){
        console.log(ans);
      }
   }); 

 }else{
    $(".followpost").append('<button class="btn btn-primary new-post">New Post</button>');
 }
    
 
}	


    //search bar
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
  
  //new post ,odal
  $(".new-post").click(function(){
  	 $("#postModal").modal('show');
  });
   
   //submitting a new post
    $("#newpost").on('submit',(function(e) {
          e.preventDefault();
          $("#message").empty();
          $('#loading').show();
          $.ajax({
          url: "api/createpost", // Url to which the request is send
          type: "POST",             // Type of request to be send, called as method
          data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
          contentType: false,       // The content type used when sending data to the server.
          cache: false,             // To unable request pages to be cached
          processData:false,        // To send DOMDocument or non processed data file it is set to false
          success: function(data)   // A function to be called if request succeeds
          {
          data = JSON.parse(data);	
          $('#loading').hide();
          $("#postModal").modal('hide');
          $('#previewing').attr('src','images/post/noimage.png');
          if(data.post_img == ""){
            $(".timelineposts").prepend('<blockquote id="post'+data[0].id+'"><p>'+data[0].body+'</p><footer>'+
            ' Posted by '+name+' at '+data[0].posted_at+
             ' <button class="btn btn-link" post-id="'+data[0].id+'" type="button" style="color: #eb3456"><span>❤0</span>&nbsp;like</button>'+
           '<button class="btn btn-link" post-ids="'+data[0].id+'" type="button" style="color: #ebf424">comments</button>'+ 
            '</footer></blockquote>');
          		  }else{
          		     		$(".timelineposts").prepend(
                                    '<blockquote><p>'+data[0].body+'</p><footer>'+
                              '<img  class="post-img post-img-new" temp-sr="'+data[0].post_img+'" id="img'+data[0].id+'"> Posted by '+name+' at '+data[0].posted_at+'<button class="btn btn-link" post-id="'+data[0].id+'" type="button" style="color: #eb3456"><span>❤0</span>&nbsp;like</button>'+
                              '<button class="btn btn-link" post-ids="'+data[0].id+'" type="button" style="color: #ebf424">comments</button>'+ 
                           '</footer></blockquote>');
          		     	}

          $("#postarea").val("");
          $("#file").val("");

          $(".post-img-new").each(function(){
              this.src = $(this).attr('temp-sr');
              this.onload = function(){
              this.style.opacity = '1';
          }});


          }
          });

    }));
    
    //when file is uploaded
    $("#file").change(function() {
        $("#message").empty(); // To remove the previous error message
        var file = this.files[0];
        var imagefile = file.type;
        var match= ["image/jpeg","image/png","image/jpg"];
        if( imagefile != match[0] && imagefile != match[1] && imagefile != match[2] )
        {
        $('#previewing').attr('src','images/post/noimage.png');
        $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
        return false;
        }
        else
        {
        var reader = new FileReader();
        reader.onload = imageIsLoaded;
        reader.readAsDataURL(this.files[0]);
    }
    });

//sending comment
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
    var	p = $("#commentModal .modal-dialog .modal-content .modal-body");
    $(p).html("");
     if(ans != "]"){
        ans = JSON.parse(ans); 
        for(i=0;i<ans.length;i++){
              $(p).append("<p>"+ans[i].comment+"  ~"+ans[i].by+"</p><hr>");
        }      
    }
 }         
 
 //load image when loaded in post box
 function imageIsLoaded(e) {
  $("#file").css("color","green");
  $('#image_preview').css("display", "block");
  $('#previewing').attr('src', e.target.result);
  $('#previewing').attr('width', '250px');
  $('#previewing').attr('height', '230px');
}

function followListenserAdd(){
           //follow user
$(".follow").click(function(event) {
  $.ajax({
    url:'api/follow?profile='+profile,
    method: 'put',
    success:function(ans){
        $(".followpost").html('<button class="btn btn-primary unfollow">Unfollow</button>');
         followListenserAdd();
    },
    error:function(ans){
      console.log(ans);
    }
  });
});

//unfollow user
$(".unfollow").click(function(event) {
  $.ajax({
    url:'api/follow?profile='+profile,
    method: 'delete',
    success:function(ans){
        $(".followpost").html('<button class="btn btn-primary follow">Follow</button>');
         followListenserAdd();
    },
    error:function(ans){
      console.log(ans);
    }
  });

});

}