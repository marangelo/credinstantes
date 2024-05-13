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
            <!-- /.card -->
            <div class="card">
              <div class="card-header" style="display:none">
              
              </div>
              <!-- /.card-header -->
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
                                <button type="button" class="btn btn-primary" id="btn-buscar-abonos">
                                    <i class="fa fa-filter"></i>
                                </button>
                                <button type="button" class="btn btn-warning" id="btn-add-employee">
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
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefono</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Contrato</th>
                        <th scope="col">Vacaciones</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($Employee as $e)
                        <tr class="align-middle">
                            <td class="align-middle white-space-nowrap path">
                                <div class="d-flex align-items-center position-relative">
                                  <div class="flex-1 ms-3">
                                      <h6 class="mb-0 fw-semi-bold"><a class="stretched-link text-900" href="EditEmployee/{{$e->id_employee}}">{{$e->first_name }} {{$e->last_name}}</a></h6>
                                      <p class="text-500 fs--2 mb-0">Descripcion  </p>
                                  </div>
                                </div>
                            </td>
                            <td class="text-nowrap">{{$e->email }}</td>
                            <td class="text-nowrap">{{$e->phone_number }}</td>
                            <td class="text-nowrap">{{$e->address }}</td>
                            <td><span class="badge badge rounded-pill d-block p-2 badge-soft-success"> Tipo de contrato  <span class="ms-1 fas fa-check" data-fa-transform="shrink-2"></span></span>
                            </td>
                            <td class="text-end">{{number_format($e->vacation_balance,2) }}</td>
                            <td class="text-end">
                            <div>
                                <button class="btn p-0" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar" onClick="Editar({{$e->id_employee}})"><span class="text-500 fas fa-edit"></span></button>
                                <button class="btn p-0 ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover" onClick="Remover({{$e->id_employee}})"><span class="text-500 fas fa-trash-alt"></span></button>
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