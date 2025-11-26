<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $fillable = ['titulo', 'archivo_path', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}