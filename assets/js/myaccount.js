$(document).ready(function($) {
	
	$.ajax({
		url: 'api/profiledetails',
	    method: 'get',
	    success: function(data){
          setAccountDetails(data);
	    },
	    error: function(error){
            console.log(error);
	    }
	});
	
	$("input[type='file']").change(function(event) {
		loadImage(this);
	});

	$("#account_form").on('submit',function(e){
     e.preventDefault();
      $.ajax({
         url : "api/changeaccountdata",
         method: "POST",
         data : new FormData(this),
         contentType: false,
         cache: false,
         processData: false, 
         success : function(data){
            setAccountDetails(data);
         },
         error:function(error){
           console.log(error);
         }
      });    
	});

});

function setAccountDetails(data){
   data = JSON.parse(data);
    $("#username").val(data.user_name);
    $("#about").val(data.about);
    $("#profilepic_img").attr("src",data.profile_img);
    $("#jumbpic_img").attr("src",data.jumbpic);
}

function loadImage(fileInput){

	    var file = fileInput.files[0];
        var imagefile = file.type;
        var match= ["image/jpeg","image/png","image/jpg"];
        if( imagefile != match[0] && imagefile != match[1] && imagefile != match[2] )
        {
        $(fileInput).after("<p id='warn' class='text-danger'>Please Select A valid Image File !! Only jpeg, jpg and png Images type allowed</p>");
        $(fileInput).css('border','1px solid red');
        return false;
        }
        else
        {
         $(fileInput).next("p").remove();
        $(fileInput).css('border','');	
        var reader = new FileReader();
        reader.onload = function(e){
            $(fileInput).prev("img").attr('src',e.target.result);
        }
        reader.readAsDataURL(file);
    }
    
}

 

