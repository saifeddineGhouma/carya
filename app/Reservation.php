<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
     protected $fillable = [
       'dateD','dateF','id_agence', 'id_voiture','id_client','statut'
        ];
}
