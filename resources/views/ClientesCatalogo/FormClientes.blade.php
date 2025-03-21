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
                <div class="col-md-10"><h4>No. de Clientes: <span id="id_clientes">{{ $Cliente->id_clientes }}</span></h4></div>
                    <div class="col-md-2">
                        <div class="btn-group w-100">                          
                            <a href="#!" class="btn btn-success" id="btn_guardar_info_cliente">
                                <i class="fas fa-plus "></i> Actualizar 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">GENERAL</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">INFO. NEGOCIO</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" 
                                        id="ref-personales-tab" 
                                        data-toggle="pill" 
                                        href="#tabs-ref-personales" 
                                        role="tab" 
                                        aria-controls="custom-tabs-one-messages" 
                                        aria-selected="false">
                                        REF. PERSONALES
                                    </a>

                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">DATOS DEL CONYUGUE</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="false">DETALLES DE GARANTIAS</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-one-tabContent">
                                    <div class="tab-pane fade" id="tabs-ref-personales" role="tabpanel" aria-labelledby="ref-personales-tab">
                                        <div class="card-body p-0">
                                            <div class="card">
                                                <div class="card-header border-transparent">
                                                    <form action="#!" method="post" id="frm_add_referencias">
                                                        <div class="row gx-2">
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">NOMBRE</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="nombre_ref" id="id_nombre_ref" placeholder="Nombre de la referencia" value="" />
                                                                </div>                                                
                                                            </div>                                                            
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">TELEFONO</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="telefono_ref" id="id_telefono_ref" placeholder="Telefono de la referencia" onkeypress='return isNumberKey(event)' maxlength="8" value="" />
                                                                </div>                                                
                                                            </div>  
                                                            <div class="col-sm-12 col-md-6 mb-3">
                                                                <label class="form-label" for="event-name">DIRECCION</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="direcion_ref" id="id_direcion_ref" placeholder="Direccion de la referencia" value="" />
                                                                    <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary" id="btn_add_referencia"><i class="fas fa-plus"></i> Agregar</button>
                                                                    </span>
                                                                </div>                                                
                                                            </div>                                                          
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover text-nowrap" id="tbl_refencias" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>NOMBRE</th>
                                                                    <th>TELEFONO</th>
                                                                    <th>DIRECCION</th>
                                                                    <th></th>
                                                                    <th>Nuevo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach ($Cliente->getReferencias as $key => $ref)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $ref->nombre_ref }}</td>
                                                                <td>{{ $ref->telefono_ref }}</td>
                                                                <td>{{ $ref->direccion_ref }}</td>
                                                                <td>
                                                                    <div>
                                                                        <button class="btn p-0 text-red ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover" onClick="Remover({{$ref->id_referencia}},'ref')"><span class="text-500 fas fa-trash-alt"></span></button>
                                                                    </div>
                                                                </td>
                                                                <td>N</td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>            
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                                        <form action="#!" method="post" id="frm_info_cliente">
                                            <div class="row gx-2">
                                                <div class="col-sm-6 col-md-4 mb-3">
                                                    <label class="form-label" for="event-name">Nombres</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="nombres" placeholder="Nombres de la persona" required="" value="{{ $Cliente->nombre ?? '' }}" />
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-4 mb-3">
                                                    <label class="form-label" for="event-name">Apellidos</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="apellidos" placeholder="Apellidos de la persona" value="{{ $Cliente->apellidos ?? '' }}" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-6 col-md-4 mb-3">
                                                    <label class="form-label" for="event-name">Cedula</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                        <input class="form-control" id="event-name" type="text" name="cedula" data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Cliente->cedula ?? '' }}"/>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-3 mb-3">
                                                    <label class="form-label" for="event-name">Telefono: </label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                        <input class="form-control" id="event-name" type="text" name="telefono" onkeypress='return isNumberKey(event)' maxlength="8" value="{{ $Cliente->telefono ?? '' }}" />
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-3 mb-3">
                                                    <label class="form-label" for="event-name">Departamento </label>
                                                    <select class="custom-select" name="selectDepartamento" id="selectDepartamento">
                                                        <option value="0">Seleccione un Departamento</option>
                                                        @foreach ($Departamentos as $d)
                                                            <option value="{{$d->id_departamento}}" {{ ($Cliente->id_departamento == $d->id_departamento) ? 'selected' : '' }}> 
                                                                {{strtoupper($d->nombre_departamento)}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-6 col-md-3 mb-3">
                                                    <label class="form-label" for="event-name">Municipio </label>
                                                    <select class="custom-select" name="selectMunicipio" id="selectMunicipio">
                                                        <option value="0">Seleccione un municipio</option>
                                                        @foreach ($Municipios as $m)
                                                            <option value="{{$m->id_municipio}}" {{ ($Cliente->id_municipio == $m->id_municipio) ? 'selected' : '' }}> 
                                                                {{strtoupper($m->nombre_municipio)}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-sm-6 col-md-3 mb-3">
                                                    <label class="form-label" for="event-name">Estado Civil</label>
                                                    <select class="custom-select" name="selectEstadoCivil" id="selectEstadoCivil">
                                                        <option value="0" {{ ($Cliente->estado_civil ?? '') == '0' ? 'selected' : '' }}>Seleccione un estado civil</option>
                                                        <option value="1" {{ ($Cliente->estado_civil ?? '') == '1' ? 'selected' : '' }}>Soltero(a)</option>
                                                        <option value="2" {{ ($Cliente->estado_civil ?? '') == '2' ? 'selected' : '' }}>Casado(a)</option>
                                                        <option value="3" {{ ($Cliente->estado_civil ?? '') == '3' ? 'selected' : '' }}>Divorciado(a)</option>
                                                        <option value="4" {{ ($Cliente->estado_civil ?? '') == '4' ? 'selected' : '' }}>Viudo(a)</option>
                                                        <option value="5" {{ ($Cliente->estado_civil ?? '') == '5' ? 'selected' : '' }}>Union Libre</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label" for="event-description">Barrio y Direccion</label>
                                                            <textarea class="form-control" rows="6" required="" name="direccion" >{{ $Cliente->direccion_domicilio ?? '' }}</textarea>
                                                        </div>                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                        <form action="#!" method="post" id="frm_info_cliente_negocio">
                                            <div class="row gx-2">
                                                <div class="col-sm-12 col-md-6 mb-3">
                                                    <label class="form-label" for="event-name">Nombre</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="nombre_negocio" placeholder="Nombres del negocio" required="" value="{{ $Cliente->getNegocio->nombre_negocio ?? '' }}" />
                                                        <div class="invalid-feedback">Campo Requerido.</div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-6 mb-3">
                                                    <label class="form-label" for="event-name">Antiguedad</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="Antiguedad_negocio" placeholder="Antiguedad del negocio" required="" value="{{ $Cliente->getNegocio->antiguedad ?? '' }}" />
                                                        <div class="invalid-feedback">Campo Requerido.</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label" for="event-description">Direccion del negocio</label>
                                                            <textarea class="form-control" rows="6" required="" name="direccion_negocio" >{{ $Cliente->getNegocio->direccion ?? '' }}</textarea>
                                                        </div>                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">    
                                        <form action="#!" method="post" id="frm_info_conyugue">                                
                                            <div class="row gx-2">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label" for="event-name">Nombres:</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="nombres_conyugue" placeholder="Nombres de la persona" required="" value="{{ $Cliente->getConyugue->nombres ?? '' }}" />
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 mb-3">
                                                    <label class="form-label" for="event-name">Apellidos:</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-user"></span></span>
                                                        <input class="form-control" type="text" name="apellidos_conyugue" placeholder="Apellidos de la persona" required="" value="{{ $Cliente->getConyugue->apellidos ?? '' }}" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-sm-6 col-md-6 mb-3">
                                                    <label class="form-label" for="event-name">Cedula:</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="far fa-address-card"></span></span>
                                                        <input class="form-control" id="event-name" type="text" name="cedula_conyugue" placeholder="000-000000-0000A" 
                                                        data-inputmask="'mask': ['999-999999-9999A']" data-mask required="" value="{{ $Cliente->getConyugue->no_cedula ?? '' }}"/>
                                                    </div>
                                                </div>

                                                <div class="col-sm-6 col-md-6 mb-3">
                                                    <label class="form-label" for="event-name">Telefono:</label>
                                                    <div class="input-group"><span class="input-group-text "><span class="fas fa-phone-alt"></span></span>
                                                        <input class="form-control" id="event-name" type="text" name="telefono_conyugue" onkeypress='return isNumberKey(event)' maxlength="8" value="{{ $Cliente->getConyugue->telefono ?? '' }}" />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <label class="form-label" for="event-description">Lugar de trabajo:</label>
                                                            <textarea class="form-control" rows="6" required="" name="direccion_conyugue" >{{ $Cliente->getConyugue->direccion_trabajo ?? '' }}</textarea>
                                                        </div>                                                
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">

                                        <div class="card-body p-0">
                                            <div class="card">
                                                <div class="card-header border-transparent">
                                                    <form action="#!" method="post" id="frm_add_garantias">
                                                        <div class="row gx-2">
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">Detalle</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="detalles" id="detalles" placeholder="Detalles" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                                </div>                                                
                                                            </div>
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">Marca</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="marca" id="marca" placeholder="Marca" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                                </div>                                                
                                                            </div>
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">Color</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="color" id="color" placeholder="Color" required="" value="{{ $Employee->first_name ?? '' }}" />
                                                                </div>                                                
                                                            </div>
                                                            <div class="col-sm-6 col-md-3 mb-3">
                                                                <label class="form-label" for="event-name">Valor</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text "><span class="fas fa-user"></span></span>
                                                                    <input class="form-control" type="text" name="valor" id="valor"
                                                                    placeholder="C$. 0.00" 
                                                                    value="{{ $Employee->first_name ?? '' }}" />
                                                                    <span class="input-group-append">
                                                                        <button type="button" class="btn btn-primary" id="btn_add_garantias"><i class="fas fa-plus"></i> Agregar</button>
                                                                    </span>
                                                                </div>    
                                                                                                            
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover text-nowrap" id="tbl_garantias" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Articulos, No. ( <span id="total_garantias">0</span> )</th>
                                                                    <th>Marca</th>
                                                                    <th>Color</th>
                                                                    <th>Valor C$.</th>
                                                                    <th> - </th>
                                                                    <th>Nuevo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach ($Cliente->getGarantias as $key => $garantia)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $garantia->detalle_articulo }}</td>
                                                                <td>{{ $garantia->marca }}</td>
                                                                <td>{{ $garantia->color }}</td>
                                                                <td>{{ $garantia->valor_recomendado }}</td>
                                                                <td>
                                                                    <div>
                                                                        <button class="btn p-0 text-red ms-2" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Remover" onClick="Remover({{$garantia->id_garantia}},'gar')"><span class="text-500 fas fa-trash-alt"></span></button>
                                                                    </div>
                                                                </td>
                                                                <td>N</td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>            
                                            </div>
                                        </div>
                                            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
        </div>
    </section>

</div>
@endsection