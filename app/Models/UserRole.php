<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    protected $table = "user_role";
    protected $primaryKey = "id";
    protected $fillable = ['role'];

    public function User()
    {
        return $this->hasMany(User::class);
    }
}
