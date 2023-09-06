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

      <form id="quickForm">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" id="email" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
          
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Acceder</button>
          </div>
          <!-- /.col -->
        </div>
      </form>



     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
@endsection
@section('metodosjs')
@include('jsViews.js_login')
@endsection
