<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

// Importante para las respuestas
// Para el index

class ProductController extends Controller
{
    /**
     * Listado paginado de productos.
     */
    public function index(): JsonResponse
    {
        // En lugar de Product::paginate(), usamos el QueryBuilder
        $products = QueryBuilder::for(Product::class)
            ->allowedFilters([
                'name',
                'status',
                AllowedFilter::exact('id'),
                AllowedFilter::scope('min_price'),
            ])
            ->allowedSorts(['name', 'price', 'created_at'])
            ->defaultSort('-created_at')
            ->paginate(10);
        $message = $products->isEmpty() ? 'No product found matching the criteria' : 'Products listed';
        return $this->successResponse(
            ProductResource::collection($products)->response()->getData(true),
            $message
        );
    }
    /**
     * Guardar un nuevo producto.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', 422, $validator->errors());
        }

        $product = Product::query()->create($request->all());

        return $this->successResponse(
            new ProductResource($product),
            'Product created',
            201
        );
    }

    /**
     * Mostrar un producto específico.
     */
    public function show(Product $product): JsonResponse
    {
        return $this->successResponse(new ProductResource($product),
            'Product found',201);
    }

    /**
     * Actualizar un producto existente.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $product->update($request->all());

        return $this->successResponse(
            new ProductResource($product),
            'Product updated'
        );
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return $this->successResponse(null, 'Product deleted', 200);
    }
}
