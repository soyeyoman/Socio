  var ID = "";  //stores id user
  var USER = ""; //stores username of user
  getUsername(""); //gets users userna
  getUserId(""); //gets id of username
   $(document).ready(function(){
          //this ajax gets the users the account has chatted with
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
                           url: 'api/profiledetails?profile='+SENDER,
                           method: 'get',
                           success: function(ans){
                             ans = JSON.parse(ans);
                               var th = $(".list-message-users").find("[user-id = "+SENDER+"]");
                               if($(th).html() != undefined){
                                    $(th).remove();
                                     $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="'+ans.profile_img+'" class="img-circle sm-image"><p>'+ans.user_name+'</p></li>');
                                    $("[user-id = "+SENDER+"]").css({
                                      background : "lightblue"
                                    });
                               }else{
                                  $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="'+ans.profile_img+'" class="img-circle sm-image"><p>'+ans.user_name+'</p></li>');
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
                       
                },
              error: function(ans) {
                   console.log(ans);
                 }  
          });//end of ajax

          
          //searching for users in the new message
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
                 //add event to the suggestions 
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

                          $.ajax({ //get details of the user selected
                        url: 'api/profiledetails?profile='+SENDER,
                        method: 'get',
                        success: function(data){
                          ans = JSON.parse(data);
                            $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+SENDER+'" class="list-group-item"><img src="'+ans.profile_img+'" class="img-circle sm-image"><p>'+name+'</p></li>');
                            $("[user-id = "+id+"]").css({
                             background : "lightblue"
                          });
                        },
                        error: function(error){
                              console.log(error);
                        }
                     });
                  
                 });
              },
              error : function(error){
                console.log(error);
              }
            });

            }
        });//new user search end

         $("#addnewmessage").click(function(){//shows user search
            $("#newmessageuser").slideDown();
         });

        
         setWebsocket();//start the chat websocket

      }); //end of document event 
        

        //gets the messages of the selected user and displays in chat area
        function getMessages(){
           $.ajax({
          url: 'api/messages0?friend='+SENDER,
          method: 'get',
          success: function(ans){ 
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
        
        //when a user is selected in the users box
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
           $(".list-message-users").prepend(' <li onclick="userClick(this)" user-id="'+id+'" class="list-group-item"><img src="'+$(obj).find('img').attr('src')+'" class="img-circle sm-image"><p>'+name+'</p></li>');

          $("[user-id = "+id+"]").css({
            background : "lightblue"
          });
          SENDER = id;
          jQuery(".read-message").html("");
          getMessages(id);
        }
//starts web socket and loads btn function
function setWebsocket(){
    var ws;
     $.ajax({
       url: 'api/userid',
       method: 'get',
       success : function(ans){
            ws = new WebSocket('ws://127.0.0.1:8989');
            var meso = {
              id: ID,
              type: 'id'
            };
            ws.onmessage = function(e){
                var result = $.parseJSON(e.data);
                if(SENDER == result.from){
                  $(".read-message").append('<div class="msg-other" >'+result.text+'</div><div class="clearfix"></div>');
                }else{
                   $(".list-message-users li[user-id='"+result.from+"']").append('<span class="new-message-tick">*</span>');
                   console.log(result.from);
                }
                     
            }

            ws.onopen = function(){
                ws.send(JSON.stringify(meso));
            }
            ws.onclose = function(){
       
           }
        },
       error: function(error) {
        console.log(error);
       }
     });
     
    
    $(".btn-send-message").click(function(e){
        var message = {
            text :  $(".send-message input[type=text]").val(),
            sender: ID,
            to : SENDER,
            from: ID,
            type : 'message'
        }
        ws.send(JSON.stringify(message));
        $(".read-message").append('<div class="msg-mine" >'+message.text+'</div><div class="clearfix"></div>');
         $(".send-message input[type=text]").val("");
        
    });
            
}//end of setwebsocket