<?php

namespace App\Http\Controllers;

use App\Models\Batiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatimentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-buildings')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $batiments = Batiment::latest()->paginate(10);
        return view('batiments.index', compact('batiments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('batiments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:batiments,nom',
            'description' => 'nullable|string',
        ]);

        Batiment::create($request->all());

        return redirect()->route('batiments.index')->with('success', 'Bâtiment créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Batiment $batiment)
    {
        return view('batiments.show', compact('batiment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Batiment $batiment)
    {
        return view('batiments.edit', compact('batiment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Batiment $batiment)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:batiments,nom,' . $batiment->id,
            'description' => 'nullable|string',
        ]);

        $batiment->update($request->all());

        return redirect()->route('batiments.index')->with('success', 'Bâtiment mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Batiment $batiment)
    {
        $batiment->delete();
        return redirect()->route('batiments.index')->with('success', 'Bâtiment supprimé avec succès.');
    }
}
