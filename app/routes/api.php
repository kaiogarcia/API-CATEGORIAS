<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SubcategoryController;

// Rotas abertas (sem autenticação)
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);

// Rotas protegidas (requer autenticação)
Route::group(["middleware" => ["auth:api"]], function(){
    // Rotas de perfil e autenticação
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("refresh-token", [ApiController::class, "refreshToken"]);
    Route::get("logout", [ApiController::class, "logout"]);

    // Rotas para categorias
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);

        // Rotas para subcategorias aninhadas em categorias
        Route::prefix('{category}/subcategories')->group(function () {
            Route::get('/', [SubcategoryController::class, 'index']);
            Route::post('/', [SubcategoryController::class, 'store']);
            Route::get('/{subcategory}', [SubcategoryController::class, 'show']);
            Route::put('/{subcategory}', [SubcategoryController::class, 'update']);
            Route::delete('/{subcategory}', [SubcategoryController::class, 'destroy']);
        });
    });
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');