@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_edit_payrolls')
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
              <div class="card-header" >
                <h3 class="card-title"></h3>
              </div>
              <div class="card-header">
                <h3 class="card-title">
                    Planilla #AD20294
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                        <button type="button" class="btn btn-warning" id="btn-add-employee">
                            <i class="fas fa-plus-circle "></i>
                        </button>
                    </li>
                    <li class="nav-item ml-2">
                        <button type="button" class="btn btn-warning" id="btn-add-employee">
                            <i class="fas fa-plus-circle "></i>
                        </button>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
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
                        </div>
                    </div>
                  </div>
                </div>
                
                <div class="table-responsive scrollbar">
                <table class="table table-striped border-bottom">
                  <thead class="light">
                    <tr class="bg-primary text-white dark__bg-1000">
                    <th class="border-0">Nombres Y Apellidos</th>
                    <th class="border-0 text-center">Cedula</th>
                    <th class="border-0 text-center">N INSS</th>
                    <th class="border-0 text-center">MES</th>
                    <th class="border-0 text-center">FECHA</th>
                    <th class="border-0 text-center">SALARIO BASICO MENSUAL</th>
                    <th class="border-0 text-center">DIAS TRABADOS</th>
                    <th class="border-0 text-center">SALARIO QUINCENAL</th>
                    <th class="border-0 text-center">COMICIONES</th>
                    <th class="border-0 text-center">NETO A PAGAR</th>
                    <th class="border-0 text-center">FIRMA</th>
                    <th class="border-0 text-center">VACACIONES</th>
                    <th class="border-0 text-center">PROV AGUINALDO</th>
                    <th class="border-0 text-center">PROV. INDEMNIZACION</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($Employes as $p)
                    <tr>
                      <td class="align-middle">
                      <a class=" text-900" href="../IngresosEgresos/{{$IdPayRoll}}/{{$p->employee_id}}"><h6 class="mb-0 text-nowrap">
                          {{$p->Employee->first_name}}  {{$p->Employee->last_name}}  </h6></a>
                        <p class="mb-0"> LA POSICION SUPUESTAMENTE</p>
                      </td>
                      <td class="align-middle text-center">{{$p->Employee->cedula_number}}</td>
                      <td class="align-middle text-end">{{$p->Employee->inss_number}}</td>
                      <td class="align-middle text-end"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      <td class="align-middle text-center"> - </td>
                      
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