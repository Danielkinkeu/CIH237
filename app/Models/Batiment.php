<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batiment extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function etages()
    {
        return $this->hasMany(Etage::class);
    }

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class);
    }
}
