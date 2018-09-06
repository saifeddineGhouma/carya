<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voiture extends Model
{
     protected $fillable = [
       'nom', 'description','imageG','id_agence'
        ];
}
