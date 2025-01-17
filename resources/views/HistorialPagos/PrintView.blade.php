<style>
/*
  Common invoice styles. These styles will work in a browser or using the HTML
  to PDF anvil endpoint.
*/

body {
  font-size: 16px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

table tr td {
  padding: 0;
}

table tr td:last-child {
  text-align: right;
}

.bold {
  font-weight: bold;
}

.right {
  text-align: right;
}

.large {
  font-size: 1.75em;
}

.total {
  font-weight: bold;
  color: #fb7578;
}

.logo-container {
  margin: 10px 0 30px 0;
}

.invoice-info-container {
  font-size: 0.875em;
}
.invoice-info-container td {
  padding: 4px 0;
}

.client-name {
  font-size: 1.5em;
  vertical-align: top;
}

.line-items-container {
  margin: 30px 0;
  font-size: 0.875em;
}

.line-items-container th {
  text-align: left;
  color: #999;
  border-bottom: 2px solid #ddd;
  border-top: 2px solid #ddd;
  padding: 10px 0 15px 0;
  font-size: 0.75em;
  text-transform: uppercase;
}

.line-items-container th:last-child {
  text-align: right;
}

.line-items-container td {
  padding: 15px 0;  
  border-bottom: 2px solid #ddd;
}

.line-items-container tbody tr:first-child td {
  padding-top: 25px;  
}

.line-items-container.has-bottom-border tbody tr:last-child td {
  padding-bottom: 25px;
  border-bottom: 2px solid #ddd;
}

.line-items-container.has-bottom-border {
  margin-bottom: 0;
}

.line-items-container th.heading-quantity {
  width: 50px;
}
.line-items-container th.heading-price {
  text-align: right;
  width: 100px;
}
.line-items-container th.heading-subtotal {
  width: 100px;
}

.payment-info {
  width: 38%;
  font-size: 0.75em;
  line-height: 1.5;
}

.footer {
  margin-top: 100px;
}

.footer-thanks {
  font-size: 1.125em;
}

.footer-thanks img {
  display: inline-block;
  position: relative;
  top: 1px;
  width: 16px;
  margin-right: 4px;
}

.footer-info {
  float: right;
  margin-top: 5px;
  font-size: 0.75em;
  color: #ccc;
}

.footer-info span {
  padding: 0 5px;
  color: black;
}

.footer-info span:last-child {
  padding-right: 0;
}

.page-container {
  display: none;
}

.footer {
  margin-top: 30px;
}

.footer-info {
  float: none;
  position: running(footer);
  margin-top: -25px;
}

.page-container {
  display: block;
  position: running(pageContainer);
  margin-top: -25px;
  font-size: 12px;
  text-align: right;
  color: #999;
}

.page-container .page::after {
  content: counter(page);
}

.page-container .pages::after {
  content: counter(pages);
}


@page {
  @bottom-right {
    content: element(pageContainer);
  }
  @bottom-left {
    content: element(footer);
  }
}
</style>
<div class="page-container">
    Fecha de Imprecion: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    <p>Impreso por: {{ Auth::user()->nombre }}</p>
</div>

<div class="logo-container">
    <table class="line-items-container has-bottom-border">
    <thead>
        <tr>
        <th>Info. Credito</th>
        <th>CREDINSTANTE.</th>
        <th>Monto Aprobado</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td class="payment-info">
            <div>
            Cliente No: <strong>{{$Credito->Clientes->id_clientes}}</strong>
            </div>
            <div>
            Credito No: <strong>{{$Credito->id_creditos}}</strong>
            </div>
        </td>
        <td class="large">Estado de Cuenta</td>
        <td class="large total">C$  {{ number_format($Credito->monto_credito,2) }}</td>
        </tr>
    </tbody>
    </table>

</div>

<table class="invoice-info-container">
    <tr>
        <td  class="client-name">{{$Credito->Clientes->nombre}} {{$Credito->Clientes->apellidos}}</td>
        <td>MONTO APROBADO: <strong>C$  {{ number_format($Credito->monto_credito,2) }}</strong></td>
    </tr>
    <tr>
      <td ></td>
      <td>CUOTA: <strong>C$  {{ number_format($Credito->cuota,2) }}</strong></td>
    </tr>
    
    
    
    <tr>
        <td>ZONA : <strong>{{$Credito->Clientes->getZona->nombre_zona}}</strong></td>
        <td>FRECUENCIA DE PAGO: <strong>Semanal</strong></td>
    </tr>
    <tr>
        <td>PLAZO : <strong>{{$Credito->plazo}}</strong></td>
        <td>FECHA DE VENCIMIENTO : <strong>{{ \Date::parse($Credito->fecha_ultimo_abono)->format('D, M d, Y')}}</strong></td>
    </tr>
    <tr>
        <td>INTERES A PAGAR: <strong>C$  {{ number_format(( $Credito->numero_cuotas * $Credito->intereses_por_cuota ),2) }}</strong></td>
        <td>FRECUENCIA DE IMPRECION: <strong>Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</strong></td>
    </tr>
</table>


<table class="line-items-container">
    <thead>
        <tr>
            <!-- <th class="heading-quantity">Qty</th>
            <th class="heading-description">Description</th>
            <th class="heading-price">Price</th>
            <th class="heading-subtotal">Subtotal</th> -->
            <th class="heading-quantity">No. RECIBO</th>
            <th class="heading-quantity">No. CUOTA</th>
            <th class="heading-Description">FECHA</th>
            <th class="heading-price">CAPITAL</th>
            <th class="heading-price">INTERES</th>
            <th class="heading-price">SALDO ANTERIOR</th>
            <th class="heading-price">SALDO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($Pagos as $a)
        <tr>
            <td class="bold">{{$a['id_abonoscreditos']}}</td>
            <td class="bold">{{$a['NumPago']}}</td>
            <td>{{$a['fecha_cuota']}}</td>
            <td class="right">C$ {{ number_format($a['pago_capital'],2) }} </td>    
            <td class="right">C$ {{ number_format($a['pago_intereses'],2) }} </td>                         
            <td class="right">C$ {{ number_format($a['saldo_anterior'],2) }}</td>
            <td class="right">{{ number_format($a['saldo_actual'],2) }}</td>
        </tr>
        @endforeach        
    </tbody>

 
</table>

