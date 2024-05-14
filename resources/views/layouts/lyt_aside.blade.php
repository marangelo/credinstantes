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
        @if( Session::get('rol') == '1' || Session::get('rol') == '2' || Session::get('rol') == '3')
          <li class="nav-item">
            <a href="{{ route('Dashboard')}}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                INDICADORES
              </p>
            </a>
          </li>        
          @endif
          <li class="nav-item menu-open">
              <a href="#" class="nav-link {{ (request()->is('Activos/*')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-table"></i>
                  <p>Clientes<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="../Activos/0" class="nav-link {{ (request()->is('Activos/*')) ? 'active' : '' }} ">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Activos</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Inactivos/0" class="nav-link {{ (request()->is('Inactivos/*')) ? 'active' : '' }} ">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Inactivos</p>
                    </a>
                </li>
                @if( Session::get('rol') == '2')
                <li class="nav-item">
                    <a href="{{ route('RecuperacionCobro')}}" class="nav-link {{ (request()->is('RecuperacionCobro')) ? 'active' : '' }} ">
                        <i class="fas fa-user nav-icon"></i>
                        <p>Recuperacion</p>
                    </a>
                </li>
                @endif
              </ul>
          </li>
          @if( Session::get('rol') == '1')
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Catalogos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            
            <ul class="nav nav-treeview">
              
             
              
              <li class="nav-item">
                <a href="{{ route('Municipios')}}" class="nav-link {{ (request()->is('Municipios')) ? 'active' : '' }}">
                  <i class="far fa-map nav-icon"></i>
                  <p>Departamento</p>
                </a>
              </li>
              <li class="nav-item" style="display:none">
                <a href="{{ route('Departamento')}}" class="nav-link {{ (request()->is('Departamento')) ? 'active' : '' }} ">
                  <i class="far fa-map nav-icon"></i>
                  <p>Departamentos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('DiasSemna')}}" class="nav-link {{ (request()->is('DiasSemna')) ? 'active' : '' }}">
                  <i class="far fa-calendar-plus nav-icon"></i>
                  <p>Dias de Semana</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('Zonas')}}" class="nav-link {{ (request()->is('Zonas')) ? 'active' : '' }}">
                  <i class="far fa-calendar-plus nav-icon"></i>
                  <p>Zonas</p>
                </a>
              </li>
            </ul>
          </li>

          @endif
          @if( Session::get('rol') == '1')
          <li class="nav-item menu-open">
              <a href="#" class="nav-link {{ (request()->is('Reporte')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
            
                <p>
                  Reportes
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
            
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('MetricasPromotor')}}" class="nav-link {{ (request()->is('MetricasPromotor')) ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Promotores</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('Visitar')}}" class="nav-link {{ (request()->is('Visitar')) ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Visitar</p>
                    </a>
                </li>

                <li class="nav-item">
                  <a href="{{route('Abonos')}}" class="nav-link {{ (request()->is('Abonos')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ingresos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('Morosidad')}}" class="nav-link {{ (request()->is('Morosidad')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Morosidad</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('Arqueos')}}" class="nav-link {{ (request()->is('Arqueos')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Arqueos</p>
                  </a>
                </li>
              </ul>
          </li>
          <li class="nav-header">FINANZAS</li>
          <li class="nav-item" >
            <a href="{{route('Payrolls')}}" class="nav-link {{ (request()->is('Payrolls') || request()->is('EditPayrolls/*') ) ? 'active' : '' }}" >
              <i class="nav-icon fas fa-money-check-alt"></i>
              <p>Planillas</p>
            </a>
          </li>
          <li class="nav-item" >
            <a href="{{route('Employee')}}" class="nav-link {{ (request()->is('Employee') || request()->is('AddEmployee') || request()->is('EditEmployee/*') ) ? 'active' : '' }}" >
              <i class="nav-icon fas fa-users"></i>
              <p>Empleados</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('Gastos')}}" class="nav-link {{ (request()->is('Gastos')) ? 'active' : '' }}" >
              <i class="nav-icon fas fa-dollar-sign"></i>
              <p>Gst. Operativos</p>
            </a>
          </li>
          <li class="nav-item" >
            <a href="{{route('Consolidado')}}" class="nav-link {{ (request()->is('Consolidado')) ? 'active' : '' }}" >
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>Cons. Indicadores</p>
            </a>
          </li>
         
          @endif
          <li class="nav-header">OPCIONES</li>
          @if( Session::get('rol') == '3')
          <li class="nav-item ">
              <a href="#" class="nav-link {{ (request()->is('Reporte')) ? 'active' : '' }}">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
            
                <p>
                  Reportes
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
            
              <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{route('Visitar')}}" class="nav-link {{ (request()->is('Visitar')) ? 'active' : '' }}" >
                        <i class="far fa-circle nav-icon"></i>
                        <p>Visitar</p>
                    </a>
                </li>

                <li class="nav-item">
                  <a href="{{route('Abonos')}}" class="nav-link {{ (request()->is('Abonos')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ingresos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('Morosidad')}}" class="nav-link {{ (request()->is('Morosidad')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Morosidad</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('Arqueos')}}" class="nav-link {{ (request()->is('Arqueos')) ? 'active' : '' }}" >
                    <i class="far fa-circle nav-icon"></i>
                    <p>Arqueos</p>
                  </a>
                </li>
                
              </ul>
          </li>
          @endif
          
          @if( Session::get('rol') == '1')
          <li class="nav-item">
              <a href="{{ route('Usuarios')}}" class="nav-link {{ (request()->is('Usuarios')) ? 'active' : '' }}">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Usuarios</p>
              </a>
          </li>
          @endif

         

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
