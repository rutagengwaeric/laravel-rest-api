<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    //

    public function index(){

        $brands = Brand::get();

        if($brands->count() > 0){

            return response()->json([
                'data' => BrandResource::collection($brands),
            ],200);
        }
        else{

            return response()->json(['message' => 'No brands found'], 200);
        }



    }
    public function store(Request $request){

        $validetor = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);


        if($validetor->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validetor->messages()
            ], 422);
        }


        $brand = Brand::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Brand created successfully',
            'data' => new BrandResource($brand)
        ],200);

    }
    public function show(Brand $brand){

       return new BrandResource($brand);

    }
    public function update(Request $request, Brand $brand){

        $validator = Validator::make($request->all(), [

            'name' => 'required|string|max:255',
            'description' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->messages()
            ], 422);

        }else{

            $brand->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            return response()->json([
                'message' => 'Brand updated successfully',
                'data' => new BrandResource($brand)
            ],200);
        }

    }
    public function destroy(Brand $brand){

        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully',
        ],200);

    }
}
