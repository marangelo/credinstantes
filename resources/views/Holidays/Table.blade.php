@extends('layouts.lyt_listas')
@section('metodosjs')
@include('Holidays.js_feriados')
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <select class="form-control " style="width: 100%;" id="id_feriado_year" name="FeriadoYear">
                          @foreach ($HolidaysGroup as $Yr)
                          <option value="{{$Yr}}">{{$Yr}}</option>
                          @endforeach
                      </select>                      
                    </div>
                  </div>                  
                  <div class="col-md-6">
                    <div class="btn-group w-100">
                          <button type="button" class="btn btn-primary" id="btn_filtrar_feriados">
                              <i class="fa fa-filter"></i> FILTRAR
                          </button>
                             
                          <button type="button" class="btn btn-success button_export_excel" id="btn-UpdateHoliday">
                              <i class="fas fa-sync-alt "></i> ACTUALIZAR
                          </button>

                          <button type="button" class="btn btn-warning" id="btn-add_feriado">
                              <i class="fas fa-calendar "></i> NUEVO 
                          </button>
                      </div>
                  </div>
                </div>           
                
                </div>
                
                <div class="table-responsive scrollbar">
                  <table class="table table-hover table-striped overflow-hidden"  id="tbl_feriados">
                    
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


     <div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            <div class="user-block">
              <img class="img-circle" src="{{asset('img/nomina.png')}}" alt="User Image">
              <span class="username"><a href="#"> <span id="accion_form"></span> Feriado </a></span>
              <span class="description"># Ingrese informacion para la fecha Feriada</span>
              <span id="IdDtFeriado" style="display:none" >0</span>
            </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

            <div class="form-group">
              <label>Fecha Feriado</label>
                <div class="input-group date" id="dtFeriado" data-target-input="nearest">
                    <div class="input-group-append" data-target="#dtFeriado" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#dtFeriado" id="IdFecha_Feriado" value="{{ date('y-m-d') }}" />
                </div>
            </div> 

            <div class="form-group">
              <label>Descripcion:</label>
              <div class="input-group date">
                <textarea class="form-control" rows="3" placeholder="Escribir ..." id="txt_descripcion"></textarea> 
              </div>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-success" id="btn_save_feriado">Guardar</button>
        </div>
      </div>
    </div>
  </div>

@endsection('content')