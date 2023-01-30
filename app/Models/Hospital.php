<?php

namespace Api\app\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Hospital extends Model
{
    public $timestamps = false;

    protected $table = 'hospitals';

    protected $fillable = ['name', 'address', 'phone'];

    public function users()
    {
        return $this->hasMany(User::class, 'workplace_id', 'id');
    }
}
