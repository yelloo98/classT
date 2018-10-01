<?php

namespace App\Http\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UsersAdmin extends Authenticatable
{
    protected $table = 'users_admin';
    protected $fillable = [
		'email', 'password',
	];
	protected $hidden = ['password'];
}
