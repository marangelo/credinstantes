@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_usuarios')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuario</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">USUARIO</li>
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
              <div class="card-header">
                <h3 class="card-title">Clientes del Sistema</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" id="btn_form_add_user" >
                      <i class="fas fa-plus text-success"></i>
                  </button>
                  
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tbl_usrs" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Permisos</th>
                    <th>Creacion</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach ($Usuarios as $u)  
                  <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ strtoupper($u->nombre) }}</td>
                    <td>{{strtoupper($u->email) }}</td>
                    <td>{{strtoupper($u->RolName->descripcion) }}</td>
                    <td>{{ Date::parse($u->created_at)->format('D, M d, Y') }}</td>
                    <th>
                    <div class="card-tools text-center">
                     
                      <button type="button" class="btn btn-tool " onclick="Edit_form({{ $u }})" >
                        <i class="fas fa-edit text-primary"></i>
                      </button>
                      <button type="button" class="btn btn-tool" onclick="Remove_form({{ $u->id }})">
                        <i class="fas fa-trash text-danger"></i>
                      </button>
                    </div>
                    </th>
                  </tr>
                  @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Creacion</th>
                    <th></th>
                  </tr>
                  </tfoot>
                </table>
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
    <div class="modal fade" id="modal_xl_add_user">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Nuevo Usuarios</h4>
              <span id="id_estado" >Estado</span>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
            <div class="card card-warning">
            
              <!-- /.card-header -->
              <div class="card-body">                  
                  <div class="row">
                    <div class="col-md-8">

                      <div class="form-group">
                        <label>Nombre Completo:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input type="text" id="txtFullName" class="form-control" placeholder="Wilmer Ramos">
                        </div>
                      </div>
                      
                    
                    </div>
                    <div class="col-md-4">
                      
                      <div class="form-group">
                        <label>Email:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                          </div>
                          <input type="text" id="txtUserName" class="form-control" placeholder="demo@demo.com">
                        </div>
                      </div>
                      
                      
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">

                      <div class="form-group">
                        <label>Contraseña:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                          </div>
                          <input type="password" id="txtPassWord_one" class="form-control"  >
                        </div>
                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">
                        <label>Confirme contraseña:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></i></span>
                          </div>
                          <input type="password" class="form-control" id="txtPassWord_two" >
                        </div>
                      </div>
                      
                    </div>
                    <div class="col-md-4">

                      <div class="form-group">
                        <label>Permiso:</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-shield"></i></i></span>
                          </div>
                          <select class="form-control" id="sclPrivi">
                            @foreach ($Roles as $r)
                              <option value="{{$r->id}}"> {{strtoupper($r->descripcion)}}</option>
                            @endforeach                       
                          </select>
                        </div>
                      </div>

                    
                    </div>
                    
                  </div>
                  
                  

                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label>Comentarios</label>
                        <textarea class="form-control" id="txtComentario" rows="3" placeholder="Algun comentario ? ..."></textarea>
                      </div>
                    </div>
                  </div>

                 
              </div>
              <!-- /.card-body -->
            </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-success" id="btn_save_user">Guardar</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <!-- /.content -->
  </div>
@endsection