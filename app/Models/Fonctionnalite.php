<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonctionnalite extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'entite_fonctionnelle_id', 'batiment_id', 'etage_id'];

    public function entiteFonctionnelle()
    {
        return $this->belongsTo(EntiteFonctionnelle::class);
    }

    public function batiment()
    {
        return $this->belongsTo(Batiment::class);
    }

    public function etage()
    {
        return $this->belongsTo(Etage::class);
    }
}
