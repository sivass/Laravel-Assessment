<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Classes\ApiResponseClass;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface){
        $this->productRepositoryInterface = $productRepositoryInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $product = $this->productRepositoryInterface->index();
        return ApiResponseClass::sendResponse(ProductResource::collection($product),'',200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
        $payload = [
            'sku' => $request->sku,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'type' => $request->type,
            'weight' => $request->weight,
            'size' => $request->size,
        ];
        DB::beginTransaction();
        try {
            //code...
            $product = $this->productRepositoryInterface->store($payload);

            DB::commit();
            return ApiResponseClass::sendResponse(new ProductResource($product),'Product Create Successfully', 201);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            //code...
            $product = $this->productRepositoryInterface->getById($id);
            return ApiResponseClass::sendResponse(new ProductResource($product),'',200);
        } catch (\Exception $ex) {
            return ApiResponseClass::rollback($ex);
        }


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        //
        $payload = [
            'sku' => $request->sku,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'type' => $request->type,
            'weight' => $request->weight,
            'size' => $request->size,
        ];
        DB::beginTransaction();
        try{
             $product = $this->productRepositoryInterface->update($payload,$id);

             DB::commit();
             return ApiResponseClass::sendResponse('Product Update Successfully','',201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $this->productRepositoryInterface->delete($id);

        return ApiResponseClass::sendResponse('Product Delete Successfully','',204);
    }
}
