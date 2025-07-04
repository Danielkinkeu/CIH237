<?php

namespace App\Http\Controllers;

use App\Models\Etage;
use App\Models\Batiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-floors')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $etages = Etage::with('batiment')->latest()->paginate(10);
        return view('etages.index', compact('etages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $batiments = Batiment::all();
        return view('etages.create', compact('batiments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|min:0',
            'batiment_id' => 'required|exists:batiments,id',
            'description' => 'nullable|string',
        ]);

        Etage::create($request->all());

        return redirect()->route('etages.index')->with('success', 'Étage créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Etage $etage)
    {
        $etage->load('batiment');
        return view('etages.show', compact('etage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Etage $etage)
    {
        $batiments = Batiment::all();
        return view('etages.edit', compact('etage', 'batiments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Etage $etage)
    {
        $request->validate([
            'numero' => 'required|integer|min:0',
            'batiment_id' => 'required|exists:batiments,id',
            'description' => 'nullable|string',
        ]);

        $etage->update($request->all());

        return redirect()->route('etages.index')->with('success', 'Étage mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Etage $etage)
    {
        $etage->delete();
        return redirect()->route('etages.index')->with('success', 'Étage supprimé avec succès.');
    }
}
