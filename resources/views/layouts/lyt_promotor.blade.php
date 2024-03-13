  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">


      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
       
      </li>
      
      
    </ul>
  </nav>
  <!-- /.navbar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#!" class="brand-link">
      <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">CREDIN$TANTES</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="card-header">
        <div class="user-block">
          <img class="img-circle" src="{{ asset('img/user.png')}}" alt="User Image">
          <span class="username"><a href="#">{{Session::get('name_session')}}</a></span>
          <span class="description mt-1" id="chance_password">Cambiar Contraseña</span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('Promotor')}}" class="nav-link active">
              <i class="fas fa-user nav-icon"></i><p>Clientes</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('Desembolsados')}}" class="nav-link">
              <i class="fas fa-user nav-icon"></i><p>Desembolsado</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('logout')}}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Salir
              </p>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>