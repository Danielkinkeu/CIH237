<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEvenement extends Model
{
    use HasFactory;

    protected $fillable = ['nom'];

    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }
}
