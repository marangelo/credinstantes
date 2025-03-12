<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm');
//Route::get('/', 'CredinstanteController@getDashboard');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('updatePassword', 'CredinstanteController@updatePassword')->name('updatePassword');;

Route::get('Dashboard', 'CredinstanteController@getDashboard')->name('Dashboard');

Route::get('Activos/{id}', 'CredinstanteController@getClientes')->name('Activos/{id}');
Route::get('Inactivos/{id}', 'CredinstanteController@getInactivos')->name('Inactivos/{id}');

Route::get('Usuarios', 'CredinstanteController@getUsuarios')->name('Usuarios');

Route::get('Municipios', 'CredinstanteController@getMunicipios')->name('Municipios');
Route::get('Departamento', 'CredinstanteController@getDepartamento')->name('Departamento');
Route::get('DiasSemna', 'CredinstanteController@getDiasSemna')->name('DiasSemna');

Route::get('Perfil/{id}', 'CredinstanteController@getPerfil')->name('Perfil/{id}');

Route::get('voucher/{id}', 'CredinstanteController@prtVoucher')->name('voucher/{id}');
Route::get('voucherParcial/{id}', 'CredinstanteController@prtVoucherParcial')->name('voucherParcial/{id}');

Route::post('SaveNewCredito', 'CredinstanteController@SaveNewCredito')->name('SaveNewCredito');
Route::post('editClient', 'CredinstanteController@editClient')->name('editClient');


Route::post('SaveNewMunicipio', 'CredinstanteController@SaveNewMunicipio')->name('SaveNewMunicipio');
Route::get('rmMunicipio/{id}', 'CredinstanteController@rmMunicipio')->name('rmMunicipio/{id}');

Route::post('SaveNewDepartamento', 'CredinstanteController@SaveNewDepartamento')->name('SaveNewDepartamento');
Route::get('rmDepartamento/{id}', 'CredinstanteController@rmDepartamento')->name('rmDepartamento/{id}');


Route::get('Zonas', 'CredinstanteController@getZona')->name('Zonas');
Route::post('AddZona', 'CredinstanteController@addZona')->name('AddZona');
Route::get('rmZona/{id}', 'CredinstanteController@rmZona')->name('rmZona/{id}');


Route::post('AddDiaSemana', 'CredinstanteController@AddDiaSemana')->name('AddDiaSemana');
Route::get('rmDiaSemana/{id}', 'CredinstanteController@rmDiaSemana')->name('rmDiaSemana/{id}');

Route::post('SaveNewAbono', 'CredinstanteController@SaveNewAbono')->name('SaveNewAbono');
Route::post('NewPagos', 'CredinstanteController@NewPagos')->name('NewPagos');
Route::get('getHistoricoAbono/{ID}', 'CredinstanteController@getHistoricoAbono')->name('getHistoricoAbono');

Route::post('AddCredito', 'CredinstanteController@AddCredito')->name('AddCredito');

//RUTAS PARA REMOVER
Route::post('rmElem', 'CredinstanteController@Remover')->name('rmElem');
Route::post('rmAbono', 'CredinstanteController@rmAbono')->name('rmAbono');
Route::post('LockUser', 'CredinstanteController@LockUser')->name('LockUser');

//RUTAS DE REPORTES
Route::get('Visitar', 'ReportsController@Visitar')->name('Visitar');
Route::get('Abonos', 'ReportsController@Abonos')->name('Abonos');
Route::get('Morosidad', 'ReportsController@Morosidad')->name('Morosidad');

Route::post('getVisitar', 'ReportsController@getVisitar')->name('getVisitar');
Route::post('getAbonos', 'ReportsController@getAbonos')->name('getAbonos');
Route::post('getMorosidad', 'ReportsController@getMorosidad')->name('getMorosidad');
Route::get('getSaldoAbono/{ID}/{OP}', 'CredinstanteController@getSaldoAbono')->name('getSaldoAbono');
Route::get('getDashboard/{ID}', 'ReportsController@getDashboard')->name('getDashboard');

Route::post('creditCheck', 'CredinstanteController@creditCheck')->name('creditCheck');
Route::post('exportAbonos', 'ReportsController@exportAbonos')->name('exportAbonos');
Route::post('exportVisita', 'ReportsController@exportVisita')->name('exportVisita');
Route::post('getAllCredit', 'CredinstanteController@getAllCredit')->name('getAllCredit');
Route::post('ChanceStatus', 'CredinstanteController@ChanceStatus')->name('ChanceStatus');

Route::get('MultiAbono', 'CredinstanteController@MultiAbonos')->name('MultiAbono');
Route::get('Bluid', 'CredinstanteController@Bluid')->name('Bluid');


Route::post('AddNewUser', 'CredinstanteController@AddNewUser')->name('AddNewUser');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('CalcularEstados', 'ApiController@CalcularEstados')->name('CalcularEstados');

Route::get('Promotor', 'CredinstanteController@Promotor')->name('Promotor');
Route::get('getDashboardPromotor/{ID}', 'ReportsController@getDashboardPromotor')->name('getDashboardPromotor');
Route::get('Desembolsados', 'CredinstanteController@Desembolsados')->name('Desembolsados');
Route::get('getClientesDesembolsados', 'ReportsController@getClientesDesembolsados')->name('getClientesDesembolsados');
Route::get('MetricasPromotor', 'CredinstanteController@MetricasPromotor')->name('MetricasPromotor');
Route::get('getMetricasPromotor/{ID}', 'ReportsController@getMetricasPromotor')->name('getMetricasPromotor');
Route::get('ExportMetricasPromotor', 'ReportsController@ExportMetricasPromotor')->name('ExportMetricasPromotor');

Route::get('RecuperacionCobro', 'ReportsController@RecuperacionCobro')->name('RecuperacionCobro');
Route::post('CalcRecuperacion', 'ReportsController@CalcRecuperacion')->name('CalcRecuperacion');

Route::get('Arqueos', 'ArqueosController@ShowHome')->name('Arqueos');
Route::get('ShowDetalles/{ZONA}', 'ArqueosController@ShowDetalles')->name('ShowDetalles');
Route::get('ArqueoInit/{ZONA}', 'ArqueosController@Init')->name('ArqueoInit');
Route::post('getDataArqueos', 'ArqueosController@getDataArqueos')->name('getDataArqueos');
Route::post('ShowDetalles/DataTableMoneda', 'ArqueosController@DataTableMoneda')->name('ShowDetalles/DataTableMoneda');
Route::post('ShowDetalles/UpdateRowArqueo', 'ArqueosController@UpdateRowArqueo')->name('ShowDetalles/UpdateRowArqueo');
Route::post('ShowDetalles/UpdateArqueo', 'ArqueosController@UpdateArqueo')->name('ShowDetalles/UpdateArqueo');
Route::post('ShowDetalles/UpdateRecuperado', 'ArqueosController@UpdateRecuperado')->name('ShowDetalles/UpdateRecuperado');
Route::post('RemoveArqueo', 'ArqueosController@RemoveArqueo')->name('RemoveArqueo');
Route::get('ExportArqueo/{ID}', 'ArqueosController@ExportArqueo')->name('ExportArqueo');


//RUTAS PARA EDITAR CREDITOS
Route::get('EditarCredito/{ID}', 'CredinstanteController@EditarCredito')->name('EditarCredito');
Route::post('UpdateCredito', 'CredinstanteController@UpdateCredito')->name('UpdateCredito');

//RUTAS DE GASTOS DE OPERACIONES
Route::get('Gastos', 'GastosOperacionesController@Gastos')->name('Gastos');
Route::post('getGastosOperaciones', 'GastosOperacionesController@getGastosOperaciones')->name('getGastosOperaciones');
Route::post('SaveGastoOperaciones', 'GastosOperacionesController@SaveGastoOperaciones')->name('SaveGastoOperaciones');
Route::post('RemoveGasto', 'GastosOperacionesController@RemoveGasto')->name('RemoveGasto');
Route::get('ExportGastos', 'GastosOperacionesController@ExportGastos')->name('ExportGastos');
route::post('getGasto', 'GastosOperacionesController@getGasto')->name('getGasto');

Route::get('Payrolls', 'PayrollsController@getPayrolls')->name('Payrolls');
Route::post('SavePayroll', 'PayrollsController@SavePayroll')->name('SavePayroll');
Route::post('EmployeeTypePayroll', 'PayrollsController@EmployeeTypePayroll')->name('EmployeeTypePayroll');
Route::get('EditPayrolls/{id_employee}', 'PayrollsController@EditPayrolls')->name('EditPayrolls/{id_employee}');
Route::post('RemovePayroll', 'PayrollsController@RemovePayroll')->name('RemovePayroll');
Route::post('UpdatePayroll', 'PayrollsController@UpdatePayroll')->name('UpdatePayroll');
Route::get('ExportPayroll', 'PayrollsController@ExportPayroll')->name('ExportPayroll');
Route::post('ProcessPayroll', 'PayrollsController@ProcessPayroll')->name('ProcessPayroll');


Route::get('Employee', 'EmployeeController@Employee')->name('Employee');
Route::get('AddEmployee', 'EmployeeController@AddEmployee')->name('AddEmployee');
Route::post('SaveEmployee', 'EmployeeController@SaveEmployee')->name('SaveEmployee');
Route::post('UpdateEmployee', 'EmployeeController@UpdateEmployee')->name('UpdateEmployee');
Route::get('/EditEmployee/{id_employee}', 'EmployeeController@editEmployee')->name('EditEmployee');
Route::post('rmEmployee', 'EmployeeController@rmEmployee')->name('rmEmployee');

Route::get('Consolidado', 'ConsolidadoController@Consolidado')->name('Consolidado');
Route::get('AddConsolidado', 'ConsolidadoController@AddConsolidado')->name('AddConsolidado');
Route::post('SaveIndiador', 'ConsolidadoController@SaveIndiador')->name('SaveIndiador');
Route::post('getIndicadores', 'ConsolidadoController@getIndicadores')->name('getIndicadores');
Route::post('RemoveIndicador', 'ConsolidadoController@RemoveIndicador')->name('RemoveIndicador');
route::post('getInfoIndicador', 'ConsolidadoController@getInfoIndicador')->name('getInfoIndicador');
Route::get('ExportConsolidado', 'ConsolidadoController@ExportConsolidado')->name('ExportConsolidado');
Route::post('UpdateConsolidado', 'ConsolidadoController@UpdateConsolidado')->name('UpdateConsolidado');

Route::get('ProxVencer', 'ProxVencerController@ProxVencer')->name('ProxVencer');
Route::post('getProxVencer', 'ProxVencerController@getProxVencer')->name('getProxVencer');



Route::get('Historial', 'ReportsController@ViewHistorialPagos')->name('Historial');
Route::post('getHistorialPagos', 'ReportsController@getHistorialPagos')->name('getHistorialPagos');
Route::get('getCreditos/{ID}', 'ReportsController@getCreditos')->name('getCreditos');
Route::get('CreditoPrint/{ID}', 'ReportsController@CreditoPrint')->name('CreditoPrint');
Route::get('PrintViewPDF/{ID}', 'ReportsController@PrintViewPDF')->name('PrintViewPDF');


Route::get('Prospectos', 'ProspectosController@ProspectosView')->name('Prospectos');
Route::post('getProspectos', 'ProspectosController@getProspectos')->name('getProspectos');
Route::get('FormPospecto/{ID}', 'ProspectosController@FormPospecto')->name('FormPospecto');
Route::post('SaveProspecto', 'ProspectosController@SaveProspecto')->name('SaveProspecto');
Route::get('deleteProspecto/{ID}', 'ProspectosController@deleteProspecto')->name('deleteProspecto');
Route::get('getInfoProspecto/{ID}', 'ProspectosController@getInfoProspecto')->name('getInfoProspecto');
Route::post('SaveNewProspecto', 'ProspectosController@SaveNewProspecto')->name('SaveNewProspecto');

Route::get('Dispensa', 'DispensaController@ViewDispensa')->name('Dispensa');
Route::post('getDispensa', 'DispensaController@getDispensa')->name('getDispensa');


Route::get('Nuevos', 'SolicitudesController@ViewNuevos')->name('Solicitudes/Lista/Nuevos');
Route::get('Renovaciones', 'SolicitudesController@ViewRenovaciones')->name('Solicitudes/Lista/Renovaciones');

Route::get('Formulario/{ID}', 'SolicitudesController@ViewForm')->name('Solicitudes/Formulario');


Route::post('getSolicitudes', 'SolicitudesController@getSolicitudes')->name('getSolicitudes');


Route::get('PagareALaOrden', 'FormatosController@PagareALaOrden')->name('PagareALaOrden');
Route::get('SolicitudCredito', 'FormatosController@SolicitudCredito')->name('SolicitudCredito');
Route::get('Pagare', 'FormatosController@Pagare')->name('Pagare');


Route::get('Supervisor', 'CredinstanteController@Supervisor')->name('Supervisor');


Route::post('UpRequestCredit', 'SolicitudesController@UpRequestCredit')->name('UpRequestCredit');
Route::get('getClientesInactivos', 'SolicitudesController@getClientesInactivos')->name('getClientesInactivos');
Route::get('CreditoRenovacion/{ID}', 'SolicitudesController@ViewFormRenovaciones')->name('CreditoRenovacion');

Route::post('RemoverRequest', 'SolicitudesController@RemoverRequest')->name('RemoverRequest');


Route::get('CatalogoClientes', 'ControllerCatalogoClientes@ViewCatalogoClientes')->name('CatalogoClientes');
Route::get('FormClientes/{id_cliente}', 'ControllerCatalogoClientes@FormClientes')->name('FormClientes');

Route::get('AddCliente', 'ControllerCatalogoClientes@AddCliente')->name('AddCliente');
Route::post('UpdateCliente', 'ControllerCatalogoClientes@UpdateCliente')->name('UpdateCliente');
Route::post('rmGarantia', 'ControllerCatalogoClientes@rmGarantia')->name('rmGarantia');
Route::post('rmReferencia', 'ControllerCatalogoClientes@rmReferencia')->name('rmReferencia');
Route::post('FiltrarClientes', 'ControllerCatalogoClientes@FiltrarClientes')->name('FiltrarClientes');
