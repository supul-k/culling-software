<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{

    protected $fillable = ['path', 'album_id','user_id','status'];
}
