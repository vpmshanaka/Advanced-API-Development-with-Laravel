<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {

            $products = Product::paginate(10);
            return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
        } catch (\Throwable $e) {
            $this->sendError('test', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $input = $request->all();
            
            $validator = Validator::make($input, [
                'name' => 'required',
                'detail' => 'required',
                'unit_price' => 'required|numeric',
            ]);
            
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            
            $product = Product::create($input);
            
            return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
        } catch (\Throwable $e) {
            return $this->sendError('Error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        try {
            $input = $request->all();
            
            $product->update($input);
            
            return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
        } catch (\Throwable $e) {
            return $this->sendError('Error', $e->getMessage());
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            return $this->sendResponse([], 'Product deleted successfully.');
        } catch (\Throwable $e) {
            return $this->sendError('Error', $e->getMessage());
        }
    }
    
}
