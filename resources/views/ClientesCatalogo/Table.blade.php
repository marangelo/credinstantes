@extends('layouts.lyt_listas')
@section('metodosjs')
@include('ClientesCatalogo.js_catalogo_clientes')
@endsection
@section('content')


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_titulo_reporte"></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">{{$Titulo}}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">              
              <div class="card-header" style="display:none"></div>
              <div class="card-body">
              @csrf
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="input-group">                      
                      <select class="form-control select2" style="width: 100%;" id="IdFilterByZone" name="IdZona">
                          <option value="-1"  selected="selected">Todos</option>
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                          @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="btn-group w-100">
                          <button type="button" class="btn btn-primary" id="btn_filter_clientes">
                              <i class="fa fa-filter"></i> FILTRAR
                          </button>
                      </div>
                  </div>
                
                </div>
                
                <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden"  id="tbl_employee">
                    <thead>
                    <tr>
                        
                        <th scope="col">NOMBRE Y APELLIDOS</th>
                        <th scope="col">ESTADO</th>
                        <th scope="col">CICLOS</th>
                        <th scope="col">TELEFONO</th>
                        <th scope="col">CEDULA</th>
                        <th class="text-end" scope="col">ACTIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($Clientes as $e)
                        <tr class="align-middle">
                            <td class="align-middle white-space-nowrap path">
                              <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ asset('/img/user-01.png') }}" alt="user image">
                                <span class="username"> <span class="text-green">[{{$e->id_clientes}}] |</span>
                                    <a href="FormClientes/{{$e->id_clientes}}"> {{ strtoupper($e->nombre) }} {{ strtoupper($e->apellidos) }} </a>                                 
                                </span>
                                <span class="description text-white">{{ strtoupper($e->direccion_domicilio) }}</span>
                              </div>
                            </td>
                            <td class="align-middle">
                                <span class="badge {{ $e->getCreditos->count() > 0 ? 'bg-'.['success','danger','warning',''][$e->getCreditos->first()->estado_credito - 1] : '' }}">
                                    {{ $e->getCreditos->count() > 0 ? strtoupper($e->getCreditos->first()->Estado->nombre_estado) : '-' }}
                                </span>
                            </td>
                            <td class="text-nowrap">{{ $e->getCreditos->count() }}</td>
                            <td class="text-nowrap">{{$e->telefono }}</td>
                            <td class="text-nowrap">{{$e->cedula }}</td>
                            <td class="text-end">
                            @if (in_array(Session::get('rol'), [1, 3]))
                            <div>
                                <button class="btn p-0 text-info" type="button" onClick="Editar({{$e->id_clientes}})"><span class="text-500 fas fa-edit"></span></button>
                                <button class="btn p-0 text-red ms-2" type="button" onClick="Remover({{$e->id_clientes}})"><span class="text-500 fas fa-trash-alt"></span></button>
                            </div>
                            @endif
                            </td>
                        </tr>
                        @endforeach     
                    </tbody>
                </table>
                </div>
              </div>
            
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>

@endsection('content')