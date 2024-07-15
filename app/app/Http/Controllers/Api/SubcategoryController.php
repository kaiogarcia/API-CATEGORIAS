<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * Listar todas as subcategorias de uma categoria específica
     */
    public function index(Category $category)
    {
        $subcategories = $category->subcategories()->paginate(10); // Paginar com 10 itens por página

        return response()->json([
            'status' => true,
            'data' => $subcategories
        ]);
    }

    /**
     * Criar uma nova subcategoria para uma categoria específica
     */
    public function store(Request $request, Category $category)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|unique:subcategories,name,NULL,id,categoria_id,' . $category->id
        ]);
    
        // Criação da subcategoria
        $subcategory = $category->subcategories()->create([
            'name' => $request->name
        ]);
    
        return response()->json([
            'status' => true,
            'message' => 'Subcategoria criada com sucesso',
            'data' => $subcategory
        ]);
    }

    /**
     * Mostrar detalhes de uma subcategoria específica
     */
    public function show(Category $category, Subcategory $subcategory)
    {
        return response()->json([
            'status' => true,
            'data' => $subcategory
        ]);
    }

    /**
     * Atualizar uma subcategoria existente
     */
    public function update(Request $request, Category $category, Subcategory $subcategory)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|unique:subcategories,name,' . $subcategory->id . ',id,categoria_id,' . $category->id
        ]);

        // Atualização da subcategoria
        $subcategory->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Subcategoria atualizada com sucesso',
            'data' => $subcategory
        ]);
    }

    /**
     * Excluir uma subcategoria
     */
    public function destroy(Category $category, Subcategory $subcategory)
    {
        $subcategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Subcategoria excluída com sucesso'
        ]);
    }
}
