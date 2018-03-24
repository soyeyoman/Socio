<?php
namespace ChatApp\Models;

class Message extends \Illuminate\Database\Eloquent\Model {
    protected $fillable = ['body','receiver','sender'];
}