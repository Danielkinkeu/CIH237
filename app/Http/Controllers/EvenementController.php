<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\TypeEvenement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EvenementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-events')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $evenements = Evenement::latest()->paginate(10);
        return view('evenements.index', compact('evenements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = TypeEvenement::all();
        return view('evenements.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:255',
            'type_evenement_id' => 'required|exists:type_evenements,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'prix' => 'nullable|numeric|min:0',
            'lien_inscription' => 'nullable|url',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('evenements_images', 'public');
        }

        Evenement::create($request->except('image') + ['image' => $imagePath]);

        return redirect()->route('evenements.index')->with('success', 'Événement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $evenement)
    {
        return view('evenements.show', compact('evenement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evenement $evenement)
    {
        $types = TypeEvenement::all();
        return view('evenements.edit', compact('evenement', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'lieu' => 'nullable|string|max:255',
            'type_evenement_id' => 'required|exists:type_evenements,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'prix' => 'nullable|numeric|min:0',
            'lien_inscription' => 'nullable|url',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = $evenement->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('evenements_images', 'public');
        }

        $evenement->update($request->except('image') + ['image' => $imagePath]);

        return redirect()->route('evenements.index')->with('success', 'Événement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evenement $evenement)
    {
        if ($evenement->image) {
            Storage::disk('public')->delete($evenement->image);
        }
        $evenement->delete();
        return redirect()->route('evenements.index')->with('success', 'Événement supprimé avec succès.');
    }
}
