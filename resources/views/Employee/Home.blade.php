@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_employee')
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
                  
                  <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar" aria-label="Buscar">
                            <div class="card-tools">
                                <button type="button" class="btn btn-success" id="btn-add-employee">
                                    <i class="fas fa-plus-circle "></i>
                                </button>
                            </div>
                          
                        </div>
                    </div>
                  </div>
                </div>
                
                <div class="table-responsive scrollbar">
                <table class="table table-hover table-striped overflow-hidden"  id="tbl_employee">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre y apellidos</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefono</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($Employee as $e)
                        <tr class="align-middle">
                          <td class="align-middle">@if($e->active == 0) Inactivo  @endif</td>
                            <td class="align-middle white-space-nowrap path">
                              <div class="user-block">
                                <img class="img-circle img-bordered-sm" src="{{ asset('/img/user-01.png') }}" alt="user image">
                                <span class="username">
                                  <a href="EditEmployee/{{$e->id_employee}}">{{$e->first_name }} {{$e->last_name}}</a> 
                                  @if($e->active == 0) <span class="badge bg-danger">Inactivo {{$e->inactive}}</span>  @endif
                                </span>
                                <span class="description">Aqui puede ir un poco mas de Informacion</span>
                              </div>
                            </td>
                            <td class="text-nowrap">{{$e->email }}</td>
                            <td class="text-nowrap">{{$e->phone_number }}</td>
                            <td class="text-end">
                            <div>
                                <button class="btn p-0 text-info" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" onClick="Editar({{$e->id_employee}})"><span class="text-500 fas fa-edit"></span></button>
                                <button class="btn p-0 text-red ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover" onClick="Remover({{$e->id_employee}})"><span class="text-500 fas fa-trash-alt"></span></button>
                            </div>
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