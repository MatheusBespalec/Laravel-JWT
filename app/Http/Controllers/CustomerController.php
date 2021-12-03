<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepository;

class CustomerController extends Controller
{
    public $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $repository = new CustomerRepository($this->customer);

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
        $request->validate($this->customer->rules());

        $customer = $this->customer->create($request->all());
        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $customer = $this->customer->find($id);
        if ($customer === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        return response()->json($customer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $customer = $this->customer->find($id);
        if ($customer === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        $request->validate($customer->rules());

        $customer->update($request->all());
        return response()->json($customer, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $customer = $this->customer->find($id);
        if ($customer === null) {
            return  response()->json(['error' => 'The resource not exists'], 404);
        }

        $customer->delete();
        return response()->json(['message' => 'Cliente removida com sucesso!'], 200);
    }
}
