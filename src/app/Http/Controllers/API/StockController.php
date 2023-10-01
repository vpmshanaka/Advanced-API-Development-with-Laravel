<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Stock;
use Validator;
use App\Http\Resources\StockResource;
use Illuminate\Http\JsonResponse;

class StockController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        
        try {
            $stocks = Stock::all();
            return $this->sendResponse(StockResource::collection($stocks), 'Stocks retrieved successfully.');
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
                'product_id' => 'integer|required|exists:products,id',
                'quantity' => 'integer|nullable',
            ]);
            
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }
            
            $stock = Stock::create($input);
            
            return $this->sendResponse(new StockResource($stock), 'Stock created successfully.');
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
        $stock = Stock::find($id);

        if (is_null($stock)) {
            return $this->sendError('Stock not found.');
        }

        return $this->sendResponse(new StockResource($stock), 'Stock retrieved successfully.');
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  Stock $stock
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function update(Request $request, Stock $stock): JsonResponse
    // {
    //     try {
    //         $input = $request->all();
            
    //         $validator = Validator::make($input, [
    //             'product_id' => 'integer|required|exists:products,id',
    //             'quantity' => 'integer|nullable',
    //         ]);
            
    //         if ($validator->fails()) {
    //             return $this->sendError('Validation Error.', $validator->errors());
    //         }
            
    //         // $stock->name = $input['name'];
    //         // $stock->detail = $input['detail'];
    //         $stock->save($input);
            
    //         return $this->sendResponse(new StockResource($stock), 'Stock updated successfully.');
    //     } catch (\Throwable $e) {
    //         return $this->sendError('Error', $e->getMessage());
    //     }
        
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  Stock $stock
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function destroy(Stock $stock): JsonResponse
    // {
    //     try {
    //         $stock->delete();
    //         return $this->sendResponse([], 'Stock deleted successfully.');
    //     } catch (\Throwable $e) {
    //         return $this->sendError('Error', $e->getMessage());
    //     }
    // }
    
}
