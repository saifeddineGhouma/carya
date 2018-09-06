<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
     protected $fillable = [
       'nom', 'logo', 'description', 'adresse','tel','email','lat','lng', 'image','id_user'
        ];
}
