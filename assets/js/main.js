
$(document).ready(function() {
	
    //gets post in timeline 
    if($("#timeline_head").html() == "Timeline"){

         $.ajax({
		url: 'api/posts',
		method: 'post',
		data: {token:"test"},
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
					  $("#postidComment").val(postid);
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
    } 
   
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


function addComment(ans){
  
 	$("#commentModal").modal('show');
    var	p = $("#commentModal .modal-dialog .modal-content .modal-body");

    if(ans != "]"){
        ans = JSON.parse(ans); 
        for(i=0;i<ans.length;i++){
              $(p).append("<p>"+ans[i].comment+"  ~"+ans[i].by+"</p><hr>");
        }      
    }

}

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