<?php

namespace App\login;

use Illuminate\Database\Eloquent\Model;

class u_user extends Model
{
    protected $primaryKey='uid';
    public $timestamps = false;
    public $table='u_user';
}
