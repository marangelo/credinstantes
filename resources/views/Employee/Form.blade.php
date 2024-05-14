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
            <form method="POST" action="{{ (isset($Employee)) ? route('UpdateEmployee') : route('SaveEmployee') }}" enctype="multipart/form-data">
            <div class="card">
              <div class="card-header" >
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Crear Nuevo empleado
                </h3>
                <div class="card-tools">
                    <button type="sumit" class="btn btn-success" id="btn-add-arqueo">
                        <i class="fas fa-save "></i>
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                @csrf
                
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex"></div>

                                    @csrf
                                    @if(session('message_success')) 
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                        {{ session('message_success') }}
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
                                            <label class="form-label" for="event-name">Email</label>
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
                                                <input class="form-control" id="event-name" type="text" name="telefono" placeholder="+505-0000-000" value="{{ $Employee->phone_number ?? '' }}" />
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label" for="event-name">Salario Mensual: </label>
                                            <div class="input-group"><span class="input-group-text "><span class="fas fa-dollar-sign"></span></span>
                                                <input class="form-control" id="event-name" type="text" name="Salario_Mensual" placeholder="C$ 00.00" value="{{ $Employee->salario_mensual ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label" for="event-name">Numero INSS</label>
                                            <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                            <input class="form-control" id="event-name" type="text" name="num_inss" placeholder="0000000-0" data-inputmask="'mask': ['9999999-9']" data-mask value="{{ $Employee->inss_number ?? '' }}" />
                                            </div>
                                        </div>
                                       
                                        <div class="col-sm-4 mb-3">
                                            <label class="form-label" for="event-name">Activo</label>
                                            <select class="custom-select" name="isActivo">
                                                <option value="1" >SI</option>
                                                <option value="0">NO</option>
                                            </select>
                                        </div>
                                                                            
                                        
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="form-label" for="event-description">Direccion</label>
                                                    <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                                </div>
                                                @if(isset($Employee))   
                                                <div class="col-12">
                                                    <label class="form-label" for="event-planillas"> </label>
                                                    <div class="card card-secondary">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Tipo de Planillas</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group">
                                                                        @foreach($PayrollTypes as $t)
                                                                        <div class="custom-control custom-checkbox mt-3">
                                                                            <input class="custom-control-input" type="checkbox" id="Checkbox_{{$t->id_payroll_type}}" data-payroll-type="{{$t->id_payroll_type}}"
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
                                                                            <label for="Checkbox_{{$t->id_payroll_type}}" class="custom-control-label">{{$t->payroll_type_name}}</label>
                                                                        </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
                            
              </div>
            
              <!-- /.card-body -->
            </div>
            </form>
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