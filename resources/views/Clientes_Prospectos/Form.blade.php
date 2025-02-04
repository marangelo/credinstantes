@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_prospectos_form')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 >CLIENTES PROSPECTOS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Prospectos</li>
              <li class="breadcrumb-item active">Formulario</li>
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
                <div class="row">                    
                  
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Nombre</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ..." value=" {{ $Prospecto->Nombres ?? '' }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ..." value="{{ $Prospecto->Apellidos ?? '' }}">
                        </div>
                      </div>
                    </div>
                 
                    <div class="col-sm-6 col-md-6">

                      <div class="form-group">
                        <label>Telefono:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtTelefono" onkeypress='return isNumberKey(event)' value="{{ $Prospecto->Telefono ?? '' }}" maxlength="8">
                        </div>
                      </div>

                    </div>

                    <div class="col-sm-6 col-md-6">

                      <div class="form-group">
                        <label>Cedula:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-address-card"></i></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask value="{{ $Prospecto->Cedula ?? '' }}">
                        </div>
                      </div>
                      
                    </div>

                    <div class="col-sm-4 col-md-4">

                      <div class="form-group">
                        <label>Monto Promedio</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                          </div>
                          <input type="text" id="txtMonto" class="form-control" placeholder="C$ 0.00" onkeypress='return isNumberKey(event)' value="{{ $Prospecto->Monto_promedio ?? '' }}">
                        </div>
                      </div>
                        
                    </div>

                    <div class="col-sm-4 col-md-4">
                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="selZona">
                            @foreach ($Zonas as $z)
                                <option value="{{$z->id_zona}}" {{ (isset($Prospecto->id_zona) && $Prospecto->id_zona == $z->id_zona) ? 'selected' : '' }}> {{strtoupper($z->nombre_zona)}}</option>
                            @endforeach
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4 col-md-4">
                      <div class="form-group">
                        <label>Giro Comercial</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" id="txtGiroComercial" class="form-control" placeholder="Nombre ..." value="{{ $Prospecto->Tipo_negocio ?? '' }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..." >{{ $Prospecto->Direccion ?? '' }}</textarea>
                      </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <input type="hidden" name="IdProspecto" id="IdProspecto" value="{{ $Prospecto->id_prospecto ?? 0 }}" >
                            <a href="#!" class="btn btn-primary btn-block" id="btn_save_prospecto" > <i class="fas fa-save"></i>  GUARDAR</a>
                        </div>
                    </div>
                    
                  </div>
                 
                
              </div>
                <!-- /.row -->
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
  </div>
@endsection