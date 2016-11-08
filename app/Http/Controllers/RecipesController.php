<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipesRequest;
use App\Http\Requests\UpdateRecipesRequest;
use Illuminate\Http\Request;
use App\Recipe;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\App;

class RecipesController extends Controller
{

    use FileUploadTrait;

    /**
     * Display all recipes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipes = Recipe::with('ingredients', 'directions')->get();
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating new Recipe.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created Recipe in storage.
     *
     * @param  \App\Http\Requests\StoreRecipesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRecipesRequest $request)
    {
        //$request = $this->saveFiles($request);
        $recipe = Recipe::create($request->all());

        // Append ingredients
        $ingredients = $request->get('ingredients');
        if(is_array($ingredients)) {
            foreach ($ingredients as $ingredient) {
                //TODO: Create function attach()
                //$recipe->ingredients()->attach($ingredient);
            }
        }

        return redirect()->route('recipes.index');
    }

    /**
     * Show the form for editing Recipe.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$ingredient = Ingredient::orderBy('name')->get();
        $recipe = Recipe::findOrFail($id);
        return view('recipes.edit', compact('recipe'));
    }

    public function show($id)
    {
        $recipe = Recipe::with('ingredients', 'directions')->findOrFail($id);
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Update Cat in storage.
     *
     * @param  \App\Http\Requests\UpdateRecipesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecipesRequest $request, $id)
    {
        $request = $this->saveFiles($request);
        $recipe = Recipe::findOrFail($id);
        $recipe->update($request->all());

        return redirect()->route('recipes.index');
    }

    /**
     * Remove Recipe from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recipe = Recipe::findOrFail($id);

        //Only delete if recipe exists
        if(!empty($recipe)){
            $recipe->delete();
        }

        return redirect()->route('recipes.index');
    }
}