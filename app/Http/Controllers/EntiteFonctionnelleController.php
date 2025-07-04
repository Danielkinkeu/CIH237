<?php

namespace App\Http\Controllers;

use App\Models\EntiteFonctionnelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntiteFonctionnelleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-functional-entities')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entites = EntiteFonctionnelle::latest()->paginate(10);
        return view('entites_fonctionnelles.index', compact('entites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('entites_fonctionnelles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:entite_fonctionnelles,nom',
            'description' => 'nullable|string',
        ]);

        EntiteFonctionnelle::create($request->all());

        return redirect()->route('entites_fonctionnelles.index')->with('success', 'Entité fonctionnelle créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EntiteFonctionnelle $entiteFonctionnelle)
    {
        return view('entites_fonctionnelles.show', compact('entiteFonctionnelle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EntiteFonctionnelle $entiteFonctionnelle)
    {
        return view('entites_fonctionnelles.edit', compact('entiteFonctionnelle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EntiteFonctionnelle $entiteFonctionnelle)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:entite_fonctionnelles,nom,' . $entiteFonctionnelle->id,
            'description' => 'nullable|string',
        ]);

        $entiteFonctionnelle->update($request->all());

        return redirect()->route('entites_fonctionnelles.index')->with('success', 'Entité fonctionnelle mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntiteFonctionnelle $entiteFonctionnelle)
    {
        $entiteFonctionnelle->delete();
        return redirect()->route('entites_fonctionnelles.index')->with('success', 'Entité fonctionnelle supprimée avec succès.');
    }
}
