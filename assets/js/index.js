var start = 0;
var postids =[];
var postcount = 0;
var working = false;

$(document).ready(function() {
	 getposts();
	 getScroll();
 });

//infinite scroll
 function getScroll(){
   $(window).scroll(function(){
      if($(this).scrollTop()+1 >= $('body').height() - $(window).height()){
          if(working == false){
             working = true;
             getposts();
          }  
      }
   });
 }

//get posts ajax
function getposts(){
	         $.ajax({
		url: 'api/posts',
		method: 'post',
		data: {token:"test",start:start},
		success: function(ans){	

		  var posts = JSON.parse(ans);
		     $.each(posts,function(index) {
             if(postids.indexOf(posts[index].postid) == -1){
                   var date = new Date(posts[index].date);
		     	if(posts[index].img == ""){
		     		$(".timelineposts").append(
                          '<blockquote id="post'+posts[index].postid+'" ><a href="profile.php?profile='+posts[index].by+'" ><h3>'+posts[index].by+'</h3> </a><h6>at '+
                          date.toGMTString()+'</h6><p>'+posts[index].body+'<footer></p><button class="btn btn-link" post-id="'+
                          posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
                    '<button class="btn btn-link" post-ids="'+posts[index].postid+'" type="button" style="color: #ebf424">comments</button>'+ 
                 '</footer></blockquote>');
		     	}else{
		     		$(".timelineposts").append(
                          '<blockquote id="post'+posts[index].postid+'" ></p><a href="profile.php?profile='+posts[index].by+'" ><h3>'+posts[index].by+'</h3> </a><h6>at '+date.toGMTString()+'</h6><p>'+posts[index].body+'</p>'+
                    '<img src="" class="post-img" temp-src="'+posts[index].img+'" id="img'+posts[index].postid+'"><div class="clearfix"><footer><button class="btn btn-link" post-id="'+
                    posts[index].postid+'" type="button" style="color: #eb3456"><span>❤'+posts[index].likes+'</span>&nbsp;'+posts[index].liked+'</button>'+
                    '<button class="btn btn-link" post-ids="'+posts[index].postid+'" type="button" style="color: #ebf424">comments</button>'+ 
                 '</footer></blockquote>');
		     	}
		     	postids[postcount++] = posts[index].postid;
             }

		     });
		     start+=5;
             setTimeout(function(){
                working = false;
             },1000);
             //add like listener
		     $('[post-id]').unbind().bind('click',function(){
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


        
