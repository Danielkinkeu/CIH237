<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntiteFonctionnelle extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class);
    }
}
