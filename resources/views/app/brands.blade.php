@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <h1>Home</h1>
@stop

@section('content')
    <card-component title="Marcas">
        <form-component btn="Pesquisar" action="">
            <div class="row">
                <div class="col-sm-6">
                    <input-component name="id" label="ID" type="number"></input-component>
                </div>
                <div class="col-sm-6">
                    <input-component name="name" label="Nome" type="text"></input-component>
                </div>
            </div>
        </form-component>
    </card-component>

    <card-component title="Resultado das Busca">
        <table-component>
            <template v-slot:thead>
                <th scope="col">#</th>
                <th scope="col">First</th>
                <th scope="col">Last</th>
                <th scope="col">Handle</th>
            </template>
            <template v-slot:tbody >
                <tr v-for="n in 5">
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
            </template>
        </table-component>
        
        <!-- Modal -->
        <modal-component id="createBrand" title="Nova Marca">
            <form-component btn="Cadastrar" action="">
                <input-component name="name" label="Nome" type="text"></input-component>
                <input-component name="image" label="Imagem" type="file"></input-component>
            </form-component>
        </modal-component>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createBrand">
            Nova Marca
        </button>
        
    </card-component>
@stop
