<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\User; // Pour les formateurs
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CoursController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('can:manage-courses')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Afficher uniquement les cours publiés au public, tous pour les admins/formateurs
        if (Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isFormateur())) {
            $cours = Cours::with('formateur')->latest()->paginate(10);
        } else {
            $cours = Cours::where('statut', 'publie')->with('formateur')->latest()->paginate(10);
        }

        return view('cours.index', compact('cours'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formateurs = User::where('role', 'formateur')->get(); // Ou des rôles qui peuvent enseigner
        return view('cours.create', compact('formateurs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'nullable|numeric|min:0',
            'image_couverture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'formateur_id' => 'required|exists:users,id',
            'niveau' => 'required|in:debutant,intermediaire,avance',
            'statut' => 'required|in:brouillon,publie,archive',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_couverture')) {
            $imagePath = $request->file('image_couverture')->store('cours_images', 'public');
        }

        Cours::create($request->except('image_couverture') + ['image_couverture' => $imagePath]);

        return redirect()->route('cours.index')->with('success', 'Cours créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cours $cour) // Utilisez $cour car $cours est la collection
    {
        if ($cour->statut !== 'publie' && (!Auth::check() || (!Auth::user()->isAdmin() && Auth::user()->id !== $cour->formateur_id))) {
            abort(404); // Empêche l'accès aux cours non publiés aux non-admins/formateurs
        }
        $cour->load('formateur'); // Charger le formateur
        return view('cours.show', compact('cour'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cours $cour)
    {
        $formateurs = User::where('role', 'formateur')->get();
        return view('cours.edit', compact('cour', 'formateurs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cours $cour)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'nullable|numeric|min:0',
            'image_couverture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'formateur_id' => 'required|exists:users,id',
            'niveau' => 'required|in:debutant,intermediaire,avance',
            'statut' => 'required|in:brouillon,publie,archive',
            'langue' => 'required|in:fr,en',
        ]);

        $imagePath = $cour->image_couverture;
        if ($request->hasFile('image_couverture')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image_couverture')->store('cours_images', 'public');
        }

        $cour->update($request->except('image_couverture') + ['image_couverture' => $imagePath]);

        return redirect()->route('cours.index')->with('success', 'Cours mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cours $cour)
    {
        if ($cour->image_couverture) {
            Storage::disk('public')->delete($cour->image_couverture);
        }
        $cour->delete();
        return redirect()->route('cours.index')->with('success', 'Cours supprimé avec succès.');
    }
}
