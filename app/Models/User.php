<?php

namespace App\Models;

use Luany\Database\Model;

class User extends Model
{
    protected string $table = 'users';

    protected bool $timestamps = true;

    /** @var array<int, string> */
    protected array $fillable = ['name', 'email'];

    /** @var array<int, string> */
    protected array $hidden = [];
}
