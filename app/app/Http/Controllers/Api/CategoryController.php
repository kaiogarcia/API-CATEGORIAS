<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Listar todas as categorias paginadas
     */
    public function index()
    {
        $categories = Category::paginate(10); // Paginar com 10 itens por página

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    /**
     * Criar uma nova categoria
     */
    public function store(Request $request)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|unique:categories'
        ]);

        // Criação da categoria
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Categoria criada com sucesso',
            'data' => $category
        ]);
    }

    /**
     * Mostrar detalhes de uma categoria específica
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }

    /**
     * Atualizar uma categoria existente
     */
    public function update(Request $request, Category $category)
    {
        // Validação
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id
        ]);

        // Atualização da categoria
        $category->update([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Categoria atualizada com sucesso',
            'data' => $category
        ]);
    }

    /**
     * Excluir uma categoria
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Categoria excluída com sucesso'
        ]);
    }
}
