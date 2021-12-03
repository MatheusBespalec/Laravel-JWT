<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;
use App\Repositories\RentalRepository;

class RentalController extends Controller
{
    public $rental;

    public function __construct(Rental $rental)
    {
        $this->rental = $rental;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new RentalRepository($this->rental);
        
        // if ($request->has('attributes_model')){
        //     $attributesBrand = 'models:id,'.$request->input('attributes_model');
        //     $repository->withRelationship($attributesBrand);
        // } else {
        //     $repository->withRelationship('model');
        // }

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
        $request->validate($this->rental->rules());

        $rental = $this->rental->create($request->all());
        return response()->json($rental, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $rental = $this->rental->find($id);
        if ($rental === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        return response()->json($rental, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function edit(Rental $rental)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $rental = $this->rental->find($id);
        if ($rental === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        } else if ($request->method() == 'PATCH' || $request->_method == 'patch' || $request->_method == 'PATCH') {
            $dynamicRules = [];
            foreach ($rental->rules() as $input => $rule) {
                if (array_key_exists($input, $request->all())) {
                    $dynamicRules[$input] = $rule;
                }
            }
            $request->validate($dynamicRules);
        } else {
            $request->validate($rental->rules());
        }

        $rental->update($request->all());
        return response()->json($rental, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rental  $rental
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $rental = $this->rental->find($id);
        if ($rental === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        $rental->delete();
        return response()->json(['message' => 'Locação removida com sucesso!'], 200);
    }
}
