@extends('layouts.lyt_main')



@section('content')
<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>503 En mantenimiento</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Credinstante</a></li>
              <li class="breadcrumb-item active">503 en mantenimiento</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-danger">503</h2>

        <div class="error-content">
          <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! En mantenimiento.</h3>

          <p>
            Lo sentimos, el sitio web esta en mantenimiento temporal. Esto significa que el servidor web no esta  disponible por el momento.
            En breve, el sitio estar  disponible de nuevo.
          </p>

        
        </div>
      </div>
      <!-- /.error-page -->

    </section>
@endsection