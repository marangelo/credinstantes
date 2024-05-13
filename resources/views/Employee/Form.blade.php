@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_employee')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
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
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Crear Nuevo empleado
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-warning" id="btn-add-arqueo">
                        <i class="fas fa-plus-circle "></i>
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @csrf
                <form method="POST" action="{{ (isset($Employee)) ? route('UpdateEmployee') : route('SaveEmployee') }}" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex"></div>

                                    @csrf
                                    @if(session('message_success')) 
                                    <div class="alert alert-success border-2 d-flex align-items-center" role="alert">
                                        <div class="bg-success me-3 icon-item"><span class="fas fa-check-circle text-white fs-3"></span></div>
                                        <p class="mb-0 flex-1">{{ session('message_success') }}</p>
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    @if(isset($Employee)) 
                                        <div class="col-12 mb-3" style="display:none">
                                        <input class="form-control" type="text" id="txt_employee" name="id_employee" value="{{ $Employee->id_employee ?? '' }}" />
                                        </div>
                                    @endif

                                    <div class="row gx-2">
                                        <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="event-name">Nombres</label>
                                        <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                            <input class="form-control" type="text" name="nombres" placeholder="Nombres de la persona" required="" value="{{ $Employee->first_name ?? '' }}" />
                                            <div class="invalid-feedback">Campo Requerido.</div>
                                        </div>
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="event-name">Apellidos</label>
                                        <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                            <input class="form-control" type="text" name="apellidos" placeholder="Apellidos de la persona" required="" value="{{ $Employee->last_name ?? '' }}" />
                                            <div class="invalid-feedback">Campo Requerido.</div>
                                        </div>
                                        </div>
                                        
                                        <div class="col-sm-4">
                                            <label class="form-label" for="event-name">EMail</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-envelope"></span></span>
                                                <input class="form-control" id="txt_email" type="text" name="email" placeholder="email@ejemplo.com" value="{{ $Employee->email ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label" for="event-name">Cedula</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                <input class="form-control" id="event-name" type="text" name="cedula" placeholder="000-000000-0000A" data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Employee->cedula_number ?? '' }}"/>
                                                <div class="invalid-feedback">Campo Requerido.</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label" for="event-name">Telefono</label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                <input class="form-control" id="event-name" type="text" name="telefono" placeholder="+505-0000-000" data-inputmask="'mask': ['+505-9999-9999']" data-mask value="{{ $Employee->phone_number ?? '' }}" />
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-sm-3 mb-3">
                                            <label class="form-label" for="event-name">Salario Mensual: </label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-dollar-sign"></span></span>
                                                <input class="form-control" id="event-name" type="text" name="num_inss" placeholder="000,000.00" data-inputmask="'mask': '999,999.99'" data-mask value="{{ $Employee->inss_number ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mb-3">
                                            <label class="form-label" for="event-name">Numero INSS</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                            <input class="form-control" id="event-name" type="text" name="num_inss" placeholder="0000000-0" data-inputmask="'mask': ['9999999-9']" data-mask value="{{ $Employee->inss_number ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mb-3">
                                            <label class="form-label" for="event-name">Planilla</label>
                                            <select class="custom-select" >
                                                <option>option 1</option>
                                                <option>option 2</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-3 mb-3">
                                            <label class="form-label" for="event-name">Activo</label>
                                            <select class="custom-select" >
                                                <option>option 1</option>
                                                <option>option 2</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                        <div class="border-dashed-bottom my-3"></div>
                                        </div>
                                        
                                        
                                        
                                       
                                        
                                        <div class="col-12">
                                        <label class="form-label" for="event-description">Direccion</label>
                                        <textarea class="form-control" rows="6" required="" name="direccion" >
                                            {{ $Employee->address ?? '' }}
                                        </textarea>
                                        </div>
                                        @if(isset($Employee))                     
                                            <div class="border-dashed-bottom my-3"></div>
                                            <h6>Planillas que pertenece </h6>
                                            @foreach($PayrollTypes as $t)
                                            <div class="form-check custom-checkbox mb-0">
                                                <input class="form-check-input" type="checkbox" data-payroll-type="{{$t->id_payroll_type}}" 
                                                    @if(isset($Employee->ListPayrollType))
                                                        @php
                                                            $found = false;
                                                            foreach ($Employee->ListPayrollType as $payrollType) {
                                                            if ($payrollType['payrolls_id'] == $t->id_payroll_type) {
                                                                $found = true;
                                                                break;
                                                            }
                                                            }
                                                        @endphp
                                                        @if($found) checked="checked" @endif
                                                    @endif />
                                                <label class="form-label mb-0"><strong>{{$t->payroll_type_name}}</strong></label>
                                                <div class="form-text mt-0">UNIDAD DE NEGOCIO</div>
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
    <!-- /.content -->
  </div>
@endsection