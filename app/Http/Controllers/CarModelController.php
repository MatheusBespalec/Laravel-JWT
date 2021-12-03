<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\CarModelRepository;

class CarModelController extends Controller
{
    public $carModel;

    public function __construct(CarModel $carModel)
    {
        $this->carModel = $carModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new CarModelRepository($this->carModel);
        
        if ($request->has('attributes_brand')){
            $attributesBrand = 'brand:id,'.$request->input('attributes_brand');
            $repository->withRelationship($attributesBrand);
        } else {
            $repository->withRelationship('brand');
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
    public function store(Request  $request)
    {
        $data = $request->validate($this->carModel->rules());

        $image = $request->file('image');
        $data['image'] = $image->store('images/car_models' ,'public');

        $carModel = $this->carModel->create($data);
        return response()->json($carModel, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $carModel = $this->carModel->with('brand')->find($id);
        if ($carModel === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        return response()->json($carModel, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function edit(CarModel $carModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CarModel  $carModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $data = $request->all();
        $carModel = $this->carModel->find($id);
        if ($carModel === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        } else if ($request->method() == 'PATCH' || $request->_method == 'patch' || $request->_method == 'PATCH') {
            $dynamicRules = [];
            foreach ($carModel->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules);
        } else {
            $request->validate($carModel->rules());
        }

        if ($request->image) {
            Storage::disk('public')->delete($carModel->image);
            $image = $request->file('image');
            $data['image'] = $image->store('images/car_models', 'public');
        }

        $carModel->update($data);

        return response()->json($carModel, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $carModel = $this->carModel->find($id);
        if ($carModel === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        Storage::disk('public')->delete($carModel->image);

        $carModel->delete();
        return response()->json(['message' => 'Modelo removido com sucesso!'], 200);
    }
}
