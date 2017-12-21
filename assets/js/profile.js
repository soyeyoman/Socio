$(document).ready(function(){
	

           $.ajax({
		url: 'api/profile_post',
		method: 'post',
		data: {token:"test",profile:profile},
		success: function(ans){	
         if(ans != "]"){
         			  var posts = JSON.parse(ans);
		     $.each(posts,function(index) {
          var date = new Date(posts[index].date);
		     	if(posts[index].img == ""){
		     		$(".timelineposts").append(
                          '<blockquote posid="'+posts[index].postid+'"><a href="profile.php?profile='+name+'" ><h3>'+name+'</h3> </a><h6>at '+date.toGMTString()+'</h6><p>'+posts[index].body+'</p><footer class="postfoot">'+
                  
                    ' <button class="btn btn-link" post-id="'+posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
                    '<button class="btn btn-link" post-ids="'+posts[index].postid+'" type="button" style="color: #ebf424">comments</button>'+ 
                 '</footer></blockquote>');
		     	}else{
		     		$(".timelineposts").append(
                          '<blockquote posid="'+posts[index].postid+'"><a href="profile.php?profile='+name+'" ><h3>'+name+'</h3> </a><h6>at '+date.toGMTString()+'</h6><p>'+posts[index].body+'</p>'+
                    '<img src="" class="post-img" temp-src="'+posts[index].img+'" id="img'+posts[index].postid+'"><div class="clearfix"></div><footer class="postfoot" ><button class="btn btn-link" post-id="'+posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
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
      
       if(user == profile){
     $("blockquote").each(function(index, el) {
       $(this).find('.postfoot').append('<button class="btn btn-link delpost" del-id="'+$(this).attr('posid')+'" style="color: #ebf424;">delete</button>');
    });
      setdelpostevent();
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
         $(".messageprofile").html('<button class="btn btn-primary message" style="margin-top:2px;">send message</button>');
      },
      error:function(ans){
        console.log(ans);
      }
   }); 

 }else{
    $(".followpost").append('<button class="btn btn-primary new-post">New Post</button>');

 }
    
  
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
           var date = new Date(data[0].posted_at);
          if(data.post_img == "" || data.post_img == undefined){
            $(".timelineposts").prepend('<blockquote posid="'+data[0].id+'"><a href="profile.php?profile='+name+'" ><h3>'+name+'</h3> </a><h6>at '+date.toGMTString()+'</h6><p>'+data[0].body+'</p><footer class="postfoot" >'+
             ' <button class="btn btn-link" post-id="'+data[0].id+'" type="button" style="color: #eb3456"><span>❤0</span>&nbsp;like</button>'+
           '<button class="btn btn-link" post-ids="'+data[0].id+'" type="button" style="color: #ebf424">comments</button>'+ 
            '</footer></blockquote>');
          		  }else{
          		     		$(".timelineposts").prepend('<blockquote posid="'+data[0].id+'"><a href="profile.php?profile='+name+'" ><h3>'+name+'</h3> </a><h6>at '+date.toGMTString()+'</h6><p>'+data[0].body+'</p>'+
                              '<img  class="post-img post-img-new" temp-sr="'+data[0].post_img+'" id="img'+data[0].id+'"><div class="clearfix"></div> <footer class="postfoot" ><button class="btn btn-link" post-id="'+data[0].id+'" type="button" style="color: #eb3456"><span>❤0</span>&nbsp;like</button>'+
                              '<button class="btn btn-link" post-ids="'+data[0].id+'" type="button" style="color: #ebf424">comments</button>'+ 
                           '</footer></blockquote>');
          		     	}
          
           $("blockquote").each(function(index, el) {
                $(this).find('.postfoot').append('<button class="btn btn-link delpost" del-id="'+$(this).attr('posid')+'" style="color: #ebf424;">delete</button>');
          });
          setdelpostevent();
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

    //message button clicked
    $(".messageprofile").click(function(){
       window.location.href="messages.php?user="+profile;
    });
});


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


function setdelpostevent(){
    $(".delpost").click(function(){
      var id = $(this).attr('del-id');
      $.ajax({
        url: 'api/post?postid='+id,
        method: 'delete',
        success: function(ans){
          $("[posid = '"+id+"']").remove();
        },
        error: function(error){
          console.log(error);
        }
      });
       
    });
}