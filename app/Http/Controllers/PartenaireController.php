<?php

namespace App\Http\Controllers;

use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-partners')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partenaires = Partenaire::latest()->paginate(10);
        return view('partenaires.index', compact('partenaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('partenaires.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type_partenaire' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_web' => 'nullable|url',
            'adresse' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'date_partenariat' => 'nullable|date',
            'statut' => 'required|in:actif,inactif',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('partenaires_logos', 'public');
        }

        Partenaire::create($request->except('logo') + ['logo' => $logoPath]);

        return redirect()->route('partenaires.index')->with('success', 'Partenaire ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partenaire $partenaire)
    {
        return view('partenaires.show', compact('partenaire'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partenaire $partenaire)
    {
        return view('partenaires.edit', compact('partenaire'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partenaire $partenaire)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'type_partenaire' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_web' => 'nullable|url',
            'adresse' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'date_partenariat' => 'nullable|date',
            'statut' => 'required|in:actif,inactif',
        ]);

        $logoPath = $partenaire->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('partenaires_logos', 'public');
        }

        $partenaire->update($request->except('logo') + ['logo' => $logoPath]);

        return redirect()->route('partenaires.index')->with('success', 'Partenaire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partenaire $partenaire)
    {
        if ($partenaire->logo) {
            Storage::disk('public')->delete($partenaire->logo);
        }
        $partenaire->delete();
        return redirect()->route('partenaires.index')->with('success', 'Partenaire supprimé avec succès.');
    }
}
