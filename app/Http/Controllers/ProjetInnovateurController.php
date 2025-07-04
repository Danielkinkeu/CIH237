<?php

namespace App\Http\Controllers;

use App\Models\ProjetInnovateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjetInnovateurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        // Permettre aux innovateurs de gérer leurs propres projets
        $this->middleware('can:manage-own-projects')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projets = ProjetInnovateur::latest()->paginate(10);
        return view('projets.index', compact('projets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_projet' => 'required|string|max:255',
            'description' => 'required|string',
            'lien_video' => 'nullable|url',
            'lien_site_web' => 'nullable|url',
            'image_projet' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statut' => 'required|in:en_cours,termine,incube',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_projet')) {
            $imagePath = $request->file('image_projet')->store('projets_images', 'public');
        }

        ProjetInnovateur::create([
            'nom_projet' => $request->nom_projet,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'lien_video' => $request->lien_video,
            'lien_site_web' => $request->lien_site_web,
            'image_projet' => $imagePath,
            'statut' => $request->statut,
            'langue' => $request->langue,
        ]);

        return redirect()->route('projets.index')->with('success', 'Projet innovateur soumis avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjetInnovateur $projet)
    {
        return view('projets.show', compact('projet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjetInnovateur $projet)
    {
        // Assurez-vous que seul l'innovateur ou un admin peut modifier
        if (Auth::user()->id !== $projet->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        return view('projets.edit', compact('projet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjetInnovateur $projet)
    {
        // Assurez-vous que seul l'innovateur ou un admin peut modifier
        if (Auth::user()->id !== $projet->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'nom_projet' => 'required|string|max:255',
            'description' => 'required|string',
            'lien_video' => 'nullable|url',
            'lien_site_web' => 'nullable|url',
            'image_projet' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'statut' => 'required|in:en_cours,termine,incube',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = $projet->image_projet;
        if ($request->hasFile('image_projet')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image_projet')->store('projets_images', 'public');
        }

        $projet->update($request->except('image_projet') + ['image_projet' => $imagePath]);

        return redirect()->route('projets.index')->with('success', 'Projet innovateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjetInnovateur $projet)
    {
        // Assurez-vous que seul l'innovateur ou un admin peut supprimer
        if (Auth::user()->id !== $projet->user_id && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($projet->image_projet) {
            Storage::disk('public')->delete($projet->image_projet);
        }
        $projet->delete();
        return redirect()->route('projets.index')->with('success', 'Projet innovateur supprimé avec succès.');
    }
}
