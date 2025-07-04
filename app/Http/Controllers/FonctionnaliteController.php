<?php

namespace App\Http\Controllers;

use App\Models\Fonctionnalite;
use App\Models\Batiment;
use App\Models\Etage;
use App\Models\EntiteFonctionnelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FonctionnaliteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-features')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fonctionnalites = Fonctionnalite::with(['entiteFonctionnelle', 'batiment', 'etage'])->latest()->paginate(10);
        return view('fonctionnalites.index', compact('fonctionnalites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $entites = EntiteFonctionnelle::all();
        $batiments = Batiment::all();
        $etages = Etage::all();
        return view('fonctionnalites.create', compact('entites', 'batiments', 'etages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entite_fonctionnelle_id' => 'required|exists:entite_fonctionnelles,id',
            'batiment_id' => 'nullable|exists:batiments,id',
            'etage_id' => 'nullable|exists:etages,id',
        ]);

        Fonctionnalite::create($request->all());

        return redirect()->route('fonctionnalites.index')->with('success', 'Fonctionnalité créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fonctionnalite $fonctionnalite)
    {
        $fonctionnalite->load(['entiteFonctionnelle', 'batiment', 'etage']);
        return view('fonctionnalites.show', compact('fonctionnalite'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fonctionnalite $fonctionnalite)
    {
        $entites = EntiteFonctionnelle::all();
        $batiments = Batiment::all();
        $etages = Etage::all();
        return view('fonctionnalites.edit', compact('fonctionnalite', 'entites', 'batiments', 'etages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fonctionnalite $fonctionnalite)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entite_fonctionnelle_id' => 'required|exists:entite_fonctionnelles,id',
            'batiment_id' => 'nullable|exists:batiments,id',
            'etage_id' => 'nullable|exists:etages,id',
        ]);

        $fonctionnalite->update($request->all());

        return redirect()->route('fonctionnalites.index')->with('success', 'Fonctionnalité mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fonctionnalite $fonctionnalite)
    {
        $fonctionnalite->delete();
        return redirect()->route('fonctionnalites.index')->with('success', 'Fonctionnalité supprimée avec succès.');
    }
}
