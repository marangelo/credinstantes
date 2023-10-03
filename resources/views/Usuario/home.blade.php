@extends('layouts.lyt_login')
@section('content')    

<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-success">
    <div class="card-header text-center">
      <div class="text-center mb-1">
        <img class="profile-user-img img-fluid img-circle" src="{{ asset('img/Logo.png') }}" alt="User profile picture">
      </div>
      <a href="#" class="h1"><b>CREDIN$TANTE</b></a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Ingrese sus Credenciales</p>

      <form id="frm_login" method="POST" action="{{route('login')}}">
      @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" id="lbl_email" placeholder="Email" value="demo@demo.com">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" id="lbl_pass" placeholder="Contrasena" value="123456">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mt-2 mb-3">
          <button type="submit" class="btn btn-credi-primary btn-block text-white">Acceder</button>
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>

@endsection

@section('metodosjs')
@include('jsViews.js_login')
@endsection
