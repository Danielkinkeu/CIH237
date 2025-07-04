<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\ProjetInnovateurController;
use App\Http\Controllers\PartenaireController;
//use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BatimentController;
use App\Http\Controllers\EtageController;
use App\Http\Controllers\EntiteFonctionnelleController;
use App\Http\Controllers\FonctionnaliteController;
use App\Http\Controllers\CoursController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function(){
    return view('welcome');
})->name('home');

// Routes pour les Articles
Route::resource('articles', ArticleController::class);

// Routes pour les Événements
Route::resource('evenements', EvenementController::class);

// Routes pour les Projets Innovateurs
Route::resource('projets', ProjetInnovateurController::class);

// Routes pour les Partenaires
Route::resource('partenaires', PartenaireController::class);

// Routes pour les Bâtiments
Route::resource('batiments', BatimentController::class);

// Routes pour les Étages
Route::resource('etages', EtageController::class);

// Routes pour les Entités Fonctionnelles
Route::resource('entites-fonctionnelles', EntiteFonctionnelleController::class);

// Routes pour les Fonctionnalités
Route::resource('fonctionnalites', FonctionnaliteController::class);

// Routes pour les Cours
Route::resource('cours', CoursController::class);


