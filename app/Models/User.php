<?php

namespace Api\app\Models;

use Illuminate\Database\Eloquent\Model as Model;

class User extends Model
{
    public $timestamps = false;

    protected $table = 'users';

    protected $fillable = ['email', 'first_name', 'last_name', 'type', 'workplace_id'];

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'workplace_id', 'id');
    }
}
