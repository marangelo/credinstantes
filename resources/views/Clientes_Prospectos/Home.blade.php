@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_prospectos')
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
              <li class="breadcrumb-item active">Prospectos</li>
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
                  <div class="col-md-6">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" id="id_txt_buscar" placeholder="Buscar cliente">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_zona" name="IdZona">
                          <option value="-1"  selected="selected">Todas las Zonas</option>
                        @foreach ($Zonas as $z)
                          <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona) }}</option>
                        @endforeach
                      </select>                      
                    </div>
                  </div>                  
                  <div class="col-md-3">
                    <div class="btn-group w-100">
                          <button type="button" class="btn btn-primary" id="btn-buscar-abonos">
                              <i class="fa fa-filter"></i> FILTRAR
                          </button>
                          
                          <button type="button" class="btn btn-success button_export_excel" id="btn-export-gasto">
                              <i class="fas fa-file-excel "></i> EXCEL
                          </button>
                          <a href="FormPospecto/0" class="btn btn-warning">
                              <i class="fas fa-user "></i> NUEVO 
                          </a>
                      </div>
                  </div>
                </div>

                <div class="row" style="{{ in_array(Auth::User()->id_rol, [1,3]) ? '' : 'display:none' }}">
                  <div class="col-md-4">
                    <div class="form-group">
                      <select class="form-control select2" style="width: 100%;" id="id_select_user" name="IdUser">
                          <option value="-1"  selected="selected">Todos los Usuarios</option>
                        @foreach ($Usuarios as $usr)
                          <option value="{{$usr->id}}"> {{strtoupper($usr->nombre) }} - [ {{ strtoupper($usr->RolName->descripcion)}}]</option>
                        @endforeach
                      </select>                      
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group date" id="dt-ini" data-target-input="nearest">
                          <input type="text" class="form-control datetimepicker-input" data-target="#dt-ini" id="dtIni" name="nmIni" value="{{ date('d/m/Y') }}"/>
                          <div class="input-group-append" data-target="#dt-ini" data-toggle="datetimepicker">
                              <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <div class="input-group date" id="dt-end" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#dt-end" id="dtEnd" name="nmEnd" value="{{ date('d/m/Y') }}"/>
                            <div class="input-group-append" data-target="#dt-end" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
                
                <table id="tbl_ingresos" class="table table-bordered table-striped">
                 
                </table>
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


  <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nueva Solicitud para Prospecto</h4> <input type="hidden" name="id_credito" id="txtIdProspecto" value="0">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="card card-warning">
            
              <!-- /.card-header -->
              <div class="card-body">                  
                  <div class="row">                    
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                          <label>Fecha Inicio</label>
                          <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="dtApertura" value="{{ date('d/m/y') }}"/>
                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                          </div>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Dia de Visita</label>
                        <select class="form-control" id="slDiaVisita">
                          @foreach ($DiasSemana as $d)
                            <option value="{{$d->id_diassemana}}"> {{strtoupper($d->dia_semana)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-4 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Promotor</label>
                        <select class="form-control" id="slPromotor">
                          @foreach ($Promo as $p)
                            <option value="{{$p->id}}"> {{strtoupper($p->nombre)}}</option>
                          @endforeach
                        </select>
                      </div>                      
                    </div>
                    <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="txtNombre" class="form-control" placeholder="Nombre ...">
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-md-4 col-xl-3">
                      <!-- text input -->
                      <div class="form-group">
                        <label>Apellido</label>
                        <input type="text" id="txtApellido" class="form-control" placeholder="Apellido ...">
                      </div>
                    </div>
                  
                    <div class="col-sm-6 col-xl-3">

                      <div class="form-group">
                        <label>Telefono:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtTelefono" maxlength="8" onkeypress='return isNumberKey(event)' >
                        </div>
                      </div>

                    </div>

                    <div class="col-sm-6 col-xl-3">

                      <div class="form-group">
                        <label>Cedula:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-address-card"></i></i></span>
                          </div>
                          <input type="text" class="form-control" id="txtCedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask>
                        </div>
                      </div>
                      
                    </div>

                    <div class="col-sm-6">

                      <div class="form-group">
                        <label>Departamento</label>
                        <select class="form-control" id="selMunicipio">
                          @foreach ($Municipios as $m)
                            <option value="{{$m->id_municipio}}"> {{strtoupper($m->nombre_municipio)}}</option>
                          @endforeach
                        </select>
                      </div>
                        
                    </div>

                    <div class="col-sm-6">

                      <div class="form-group">
                        <label>Zonas</label>
                        <select class="form-control" id="selZona">
                          @foreach ($Zonas as $z)
                            <option value="{{$z->id_zona}}"> {{strtoupper($z->nombre_zona)}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                    </div>
                    
                
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>DIRECCION</label>
                        <textarea class="form-control" id="txtDireccion" rows="3" placeholder="Direcion ..."></textarea>
                      </div>
                    </div>
                
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Monto</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtMonto" class="form-control"  
                            data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Plazo</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtPlazo" class="form-control" 
                            data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Interes</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            </div>
                            <input type="text" id="txtInteres" class="form-control" 
                            data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>N° Cuotas</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" id="txtCuotas" class="form-control" 
                            data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 0, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Total</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="txt" id="txtTotal" class="form-control" 
                            disabled data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Cuota</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtVlCuota" class="form-control" 
                            disabled data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Saldos</label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtSaldos" class="form-control" 
                            disabled data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Intereses </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtIntereses" class="form-control" 
                            disabled data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-xl-4">
                      <div class="form-group">
                        <label>Intereses por cuota </label>
                          <div class="input-group mb-3">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            </div>
                            <input type="text" id="txtInteresesPorCuota" class="form-control" 
                            disabled data-mask data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '', 'placeholder': '0'">
                          </div>
                      </div>
                    </div>
                  </div>
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-danger" id="btn_save_credito">Crear Solicitud</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
@endsection