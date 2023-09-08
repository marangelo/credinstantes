@extends('layouts.lyt_login')
@section('content')    
<div class="login-box">
  <div class="login-logo">
    <a href="#!"><b>Credin</b>Stantes</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicia sesión para iniciar tu sesión</p>

      <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- form start -->
            <form id="frm_login" method="POST" action="{{route('login')}}">
            @csrf
                <div class="form-group">
                    <label for="lbl_email">Email</label>
                    
                    <input type="email" name="email" class="form-control" id="lbl_email" placeholder="Digite email" value="demo@demo.com">
                </div>
                <div class="form-group">
                    <label for="lbl_pass">Contrasena</label>
                    <input type="password" name="password" class="form-control" id="lbl_pass" placeholder="Contrasena" value="123456">
                </div>
                <!-- /.card-body -->
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block">Acceder</button>
                </div>
              </form>
            </div>
          <!--/.col (left) -->
         
        </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
@endsection

@section('metodosjs')
@include('jsViews.js_login')
@endsection
