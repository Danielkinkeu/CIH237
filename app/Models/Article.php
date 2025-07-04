<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'slug',
        'contenu',
        'image_principale',
        'user_id',
        'date_publication',
        'statut',
        'langue',
    ];

    protected $casts = [
        'date_publication' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
