<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Repositories\CarRepository;

class CarController extends Controller
{
    public $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new CarRepository($this->brand);
        
        if ($request->has('attributes_model')){
            $attributesBrand = 'models:id,'.$request->input('attributes_model');
            $repository->withRelationship($attributesBrand);
        } else {
            $repository->withRelationship('model');
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
        $request->validate($this->carModel->rules());

        $car = $this->car->create($request->all());
        return response()->json($car, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $car = $this->car->with('brand')->find($id);
        if ($car === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        return response()->json($car, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $car = $this->car->find($id);
        if ($car === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        } else if ($request->method() == 'PATCH' || $request->_method == 'patch' || $request->_method == 'PATCH') {
            $dynamicRules = [];
            foreach ($car->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules);
        } else {
            $request->validate($car->rules());
        }

        $car->update($request->all());
        return response()->json($car, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $car = $this->car->find($id);
        if ($car === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        $car->delete();
        return response()->json(['message' => 'Carro removida com sucesso!'], 200);
    }
}
