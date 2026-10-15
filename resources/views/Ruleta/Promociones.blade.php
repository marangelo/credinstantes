@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_ruleta_promociones')
@endsection
@section('content')
<div class="wrapper">

  <!-- Main Sidebar Container -->
  @include('layouts.lyt_aside')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">PROMOCIONES
              <span class="badge badge-warning align-middle ml-2" id="lbl_demo" style="display:none; font-size: .9rem;">DATOS DE EJEMPLO</span>
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item">Ruleta</li>
              <li class="breadcrumb-item active">Promociones</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title mb-0 mt-1">Límites de premios por día y por semana (lunes a domingo). Al llegar al límite la app no deja girar.</h5>
                <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm" id="btn_nueva_promocion">
                    <i class="fas fa-plus"></i> Nueva promoción
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="tbl_ruleta_promociones" class="table table-bordered table-striped" style="width:100%"></table>
              </div>
            </div>
          </div>
        </div>

      </div><!--/. container-fluid -->

      <div class="modal fade" id="modal_promocion">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="lbl_modal_promocion">Nueva promoción</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="txt_id_promocion" value="0">

              <div class="form-group">
                <!-- Nombre a la izquierda y el interruptor de activa a la altura del label -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label for="txt_nombre" class="mb-0">Nombre</label>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="chk_activa">
                    <label class="custom-control-label" for="chk_activa">Promoción activa</label>
                  </div>
                </div>
                <input type="text" class="form-control" id="txt_nombre" maxlength="100" placeholder="Ej. Ruleta de la Primavera">
              </div>

              <div class="form-group">
                <label for="txt_vigencia">Vigencia</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                  </div>
                  <input type="text" class="form-control" id="txt_vigencia">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="txt_limite_diario">Límite diario</label>
                    <div class="input-group">
                      <input type="number" class="form-control" id="txt_limite_diario" min="1" step="1">
                      <div class="input-group-append"><span class="input-group-text">premios / día</span></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="txt_limite_semanal">Límite semanal</label>
                    <div class="input-group">
                      <input type="number" class="form-control" id="txt_limite_semanal" min="1" step="1">
                      <div class="input-group-append"><span class="input-group-text">premios / semana</span></div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <!-- Label de premios con el botón de agregar a la misma altura -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label class="mb-0">Premios</label>
                  <button type="button" class="btn btn-sm btn-outline-primary" id="btn_agregar_premio">
                    <i class="fas fa-plus"></i> Agregar premio
                  </button>
                </div>
                <table class="table table-sm mb-1" id="tbl_premios_editor">
                  <thead>
                    <tr>
                      <th style="width: 26%">Tipo</th>
                      <th>Monto C$ / Objeto</th>
                      <th style="width: 16%" title="Probabilidad relativa: con todos en 1 cada premio tiene la misma chance">Peso</th>
                      <th style="width: 12%" class="text-right">Chance</th>
                      <th style="width: 1%"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
                <small class="form-text text-muted">El peso define qué tan seguido sale cada premio. Con todos en 1, todos tienen la misma chance. En la ruleta todas las rebanadas se ven del mismo tamaño.</small>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btn_save_promocion">Guardar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('layouts.lyt_footer')
</div>
@endsection
