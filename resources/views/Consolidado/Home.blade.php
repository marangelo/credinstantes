@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_consolidado')
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 id="lbl_titulo_reporte">{{$Titulo}}</h1>
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
              
              <div class="table-responsive scrollbar">
                <table class="table table-striped border-bottom" id="tbl_consolidado">
                  <thead class="light">
                    <tr class="bg-primary text-white dark__bg-1000">
                      <th></th>
                      <th class="border-0">COMPARATIVO DEL MES</th>
                      <th class="border-0 text-center">JUNIO</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr><td>1</td><td>NUMERO DE CLIENTES</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>2</td><td>SALDOS DE CARTERA</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>3</td><td>EFECTIVO DISPONIBLE  DE CAP</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>4</td><td>DESEMBOLSOS  DEL MES</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>5</td><td>CLIENTES NUEVOS</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>6</td><td>RENOVACIONES</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>7</td><td>MORA ATRASADA</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>8</td><td>MORA VENCIDA</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>9</td><td>RECUPERACION </td><td class="align-middle text-center">0</td></tr>
                    <tr><td>10</td><td>INGRESOS FINANCIEROS</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>11</td><td>GASTOS OPERATIVOS</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>12</td><td>UTILIDADES DE ACCIONISTAS REINVERTIDAS</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>13</td><td>REINVERCION DE INTERESES / P CAPITAL</td><td class="align-middle text-center">0</td></tr>
                    <tr><td>14</td><td>UTILIDADES RETENIDAS PARA PROVICION</td><td class="align-middle text-center">0</td></tr>
                  </tbody>
                    
                </table>
              </div>
            
            </div>
            <!-- /.card -->
          </div>
               
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
    <!-- /.content -->
  </div>
@endsection