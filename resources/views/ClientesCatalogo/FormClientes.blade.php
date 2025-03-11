@extends('layouts.lyt_listas')

@section('metodosjs')
@include('ClientesCatalogo.js_catalogo_clientes')
@endsection

@section('content')
<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 id="lbl_titulo_reporte"></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">{{$Titulo}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-10"><h4>{{ $Titulo }}</h4></div>
                    <div class="col-md-2">
                        <div class="btn-group w-100">                          
                            <a href="FormPospecto/0" class="btn btn-success">
                                <i class="fas fa-plus "></i> Guardar 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ (isset($Cliente)) ? route('AddCliente') : route('AddCliente') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">General</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Info. Negocio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Datos del Conyugue</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">Detalles de Garantias</a>
                                </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <div class="row gx-2">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="event-name">Nombres</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Nombres de la persona" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="event-name">Apellidos</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="apellidos" placeholder="Apellidos de la persona" required="" value="{{ $Employee->last_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Cedula</label>
                                                <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                    <input class="form-control" id="event-name" type="text" name="cedula" placeholder="000-000000-0000A" data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Employee->cedula_number ?? '' }}"/>
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Telefono</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                    <input class="form-control" id="event-name" type="text" name="telefono" placeholder="+505-0000-000" value="{{ $Employee->phone_number ?? '' }}" />
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Municipio</label>
                                                <select class="custom-select" name="isActivo">

                                                    <option value="0">Si</option>

                                                </select>
                                            </div>

                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Estado Civil</label>
                                                <select class="custom-select" name="isActivo">

                                                    <option value="1">Soltero(a)</option>
                                                    <option value="2">Casado(a)</option>
                                                    <option value="3">Divorciado(a)</option>
                                                    <option value="4">Viudo(a)</option>

                                                </select>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label" for="event-description">Barrio y Direccion</label>
                                                        <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <div class="row gx-2">
                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <label class="form-label" for="event-name">Nombres</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Nombres del negocio" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 mb-3">
                                                <label class="form-label" for="event-name">Antiguedad</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="apellidos" placeholder="Antiguedad del negocio" required="" value="{{ $Employee->last_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label" for="event-description">Direccion del negocio</label>
                                                        <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">                                    
                                        <div class="row gx-2">
                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="event-name">Nombres</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Nombres de la persona" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 mb-3">
                                                <label class="form-label" for="event-name">Apellidos</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="apellidos" placeholder="Apellidos de la persona" required="" value="{{ $Employee->last_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-6 col-md-6 mb-3">
                                                <label class="form-label" for="event-name">Cedula</label>
                                                <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                    <input class="form-control" id="event-name" type="text" name="cedula" placeholder="000-000000-0000A" data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Employee->cedula_number ?? '' }}"/>
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-6 mb-3">
                                                <label class="form-label" for="event-name">Telefono</label>
                                                <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                    <input class="form-control" id="event-name" type="text" name="telefono" placeholder="+505-0000-000" value="{{ $Employee->phone_number ?? '' }}" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label class="form-label" for="event-description">Nombre y Direccion</label>
                                                        <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Employee->address ?? '' }}</textarea>
                                                    </div>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                                    <div class="card-body p-0">
                                        <div class="row gx-2">
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Detalle</label>
                                                <div class="input-group">
                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Detalles" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>                                                
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Marca</label>
                                                <div class="input-group">
                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Marca" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>                                                
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Color</label>
                                                <div class="input-group">
                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="Color" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                </div>                                                
                                            </div>
                                            <div class="col-sm-6 col-md-3 mb-3">
                                                <label class="form-label" for="event-name">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                    <input class="form-control" type="text" name="nombres" placeholder="C$. 0.00" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                    <div class="invalid-feedback">Campo Requerido.</div>
                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar</button>
                                                    </span>
                                                </div>    
                                                                                            
                                            </div>
                                        </div>
                                      
                                        <ul class="products-list product-list-in-card pl-2 pr-2 mt-3">
                                            <div class="col-12">
                                                <h4>
                                                    <i class="fas fa-globe"></i> Articulos, No.
                                                    <small class="float-right">( 4 )</small>
                                                </h4>
                                            </div>
                                            <li class="item">
                                                <div class="product-img">
                                                <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                                </div>
                                                <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">Samsung TV
                                                    <span class="badge badge-warning float-right">C$1800</span></a>
                                                <span class="product-description">
                                                    Samsung 32" 1080p 60Hz LED Smart HDTV.
                                                </span>
                                                </div>
                                            </li>
                                            <!-- /.item -->
                                            <li class="item">
                                                <div class="product-img">
                                                <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                                </div>
                                                <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">Bicycle
                                                    <span class="badge badge-info float-right">C$700</span></a>
                                                <span class="product-description">
                                                    26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                                                </span>
                                                </div>
                                            </li>
                                            <!-- /.item -->
                                            <li class="item">
                                                <div class="product-img">
                                                <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                                </div>
                                                <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">
                                                    Xbox One <span class="badge badge-danger float-right">
                                                    C$350
                                                </span>
                                                </a>
                                                <span class="product-description">
                                                    Xbox One Console Bundle with Halo Master Chief Collection.
                                                </span>
                                                </div>
                                            </li>
                                            <!-- /.item -->
                                            <li class="item">
                                                <div class="product-img">
                                                <img src="{{ asset('img/default-150x150.png') }}" alt="Product Image" class="img-size-50">
                                                </div>
                                                <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">PlayStation 4
                                                    <span class="badge badge-success float-right">C$399</span></a>
                                                <span class="product-description">
                                                    PlayStation 4 500GB Console (PS4)
                                                </span>
                                                </div>
                                            </li>
                                        <!-- /.item -->
                                        </ul>
                                    </div>
                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>            
            </form>
        </div>
    </section>

</div>
@endsection