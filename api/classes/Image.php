<?php

class Image{
	
	public function ImageUpload($imgname,$imgtype,$query,$params){
		$target_file = basename($imgname);
   	   $file_type = pathinfo($_FILES[$imgtype]['name'])['extension'];

    if($file_type != 'jpg' && $file_type != 'png' && $file_type != 'jpeg'){
       echo "Unsupported image type";
    }else{
      if($_FILES[$imgtype]['size'] > 10240000){
        echo "File too large";
      }else{
         $target_file .= '.'.$file_type;
         
         if($query == "profile"){
         	$query = "UPDATE users SET profile_img = :img WHERE id = :id";
         	$target_file = "images/profile/".$target_file;
         }elseif ($query == "post") {
            $query = "UPDATE posts SET post_img = :img WHERE id = :id";
            $target_file = "images/post/".$target_file;
         }
         move_uploaded_file($_FILES[$imgtype]['tmp_name'],$target_file);
         DB::query($query,array(':img' => $target_file,':id' => $params));
         
      }
    	
    }
	}
}