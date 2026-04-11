<?php

namespace App\Models;

use Luany\Database\Model;

class User extends Model
{
    protected string $table      = 'users';
    protected string $primaryKey = 'id';
    protected array  $fillable   = ['name', 'email'];
    protected array  $hidden     = [];
    protected array  $casts      = [];
}