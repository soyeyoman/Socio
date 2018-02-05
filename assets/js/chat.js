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
                     $(".read-message").append('<div class="msg-other" >'+e.data+'</div><div class="clearfix"></div>');
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
            type : 'message'
        }
        ws.send(JSON.stringify(message));
        $(".read-message").append('<div class="msg-mine" >'+message.text+'</div><div class="clearfix"></div>');
         $(".send-message input[type=text]").val("");
        
    });
            
}