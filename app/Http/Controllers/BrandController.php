<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public $brand;

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new BrandRepository($this->brand);
        
        if ($request->has('attributes_models')){
            $attributesBrand = 'models:id,'.$request->input('attributes_models');
            $repository->withRelationship($attributesBrand);
        } else {
            $repository->withRelationship('models');
        }

        if ($request->has('filters')){
            $repository->withFilters($request->input('filters'));
        }

        if ($request->has('attributes')){
            $repository->withAttributes($request->input('attributes'));
        }

        return response()->json($repository->getResults(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->brand->rules());

        $image = $request->file('image');
        $nameImage = $image->store('images/brands' ,'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $nameImage
        ]);
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {   
        $brand = $this->brand->with('models')->find($id);
        if ($brand === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        return response()->json($brand, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $brand = $this->brand->find($id);
        if ($brand === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        } else if ($request->method() == 'PATCH' || $request->_method == 'patch' || $request->_method == 'PATCH') {
            $dynamicRules = [];
            foreach ($brand->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules);
        } else {
            $request->validate($brand->rules());
        }

        if ($request->image) {
            Storage::disk('public')->delete($brand->image);
            $image = $request->file('image');
            $data['image'] = $image->store('images/brands', 'public');
        }

        $brand->update($data);

        return response()->json($brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $brand = $this->brand->find($id);
        if ($brand === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        Storage::disk('public')->delete($brand->image);

        $brand->delete();
        return response()->json(['message' => 'Marca removida com sucesso!'], 200);
    }
}
