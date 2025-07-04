<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        // Seuls les admins ou utilisateurs avec rôle 'formateur' peuvent gérer les articles
        $this->middleware('can:manage-articles')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::where('statut', 'publie')->latest()->paginate(10);
        return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statut' => 'required|in:brouillon,publie',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_principale')) {
            $imagePath = $request->file('image_principale')->store('articles_images', 'public');
        }

        Article::create([
            'titre' => $request->titre,
            'slug' => Str::slug($request->titre . '-' . uniqid()),
            'contenu' => $request->contenu,
            'image_principale' => $imagePath,
            'user_id' => Auth::id(), // L'utilisateur connecté est l'auteur
            'date_publication' => $request->statut === 'publie' ? now() : null,
            'statut' => $request->statut,
            'langue' => $request->langue,
        ]);

        return redirect()->route('articles.index')->with('success', 'Article créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        if ($article->statut === 'brouillon' && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(404); // Empêche l'accès aux brouillons non-admin
        }
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'image_principale' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statut' => 'required|in:brouillon,publie',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = $article->image_principale;
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image si elle existe
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image_principale')->store('articles_images', 'public');
        }

        $article->update([
            'titre' => $request->titre,
            'slug' => Str::slug($request->titre . '-' . uniqid()), // Peut être mis à jour ou conservé
            'contenu' => $request->contenu,
            'image_principale' => $imagePath,
            'date_publication' => ($request->statut === 'publie' && is_null($article->date_publication)) ? now() : $article->date_publication,
            'statut' => $request->statut,
            'langue' => $request->langue,
        ]);

        return redirect()->route('articles.index')->with('success', 'Article mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        if ($article->image_principale) {
            Storage::disk('public')->delete($article->image_principale);
        }
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article supprimé avec succès.');
    }
}
