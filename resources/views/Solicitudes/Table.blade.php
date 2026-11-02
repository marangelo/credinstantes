@extends('layouts.lyt_listas')

@section('metodosjs')
@include('jsViews.js_solicitudes')
@endsection

@section('content')
@php
  $esRenovar = trim($lblBtn) === 'RENOVAR';
  $titulo    = $esRenovar ? 'Renovaciones' : 'Solicitudes nuevas';
  $accion    = $esRenovar ? 'Renovar cliente' : 'Nueva solicitud';
@endphp

<link rel="stylesheet" href="{{ asset('css/sol-lista.css') }}?v={{ filemtime(public_path('css/sol-lista.css')) }}">

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid sl">
      @csrf
      {{-- El JS lee este texto (NUEVAS / RENOVAR) para saber qué lista cargar --}}
      <span id="lbl_titulo_reporte" class="d-none">{{ $lblBtn }}</span>

      <header class="sl-header">
        <div>
          <h1 class="sl-title">{{ $titulo }}</h1>
          <p class="sl-subtitle"><span id="slTotal">—</span> solicitudes pendientes</p>
        </div>
        <a href="#!" role="button" class="sl-btn sl-btn--primary" id="btn_open_modal_renovacion">
          <i class="fas {{ $esRenovar ? 'fa-redo' : 'fa-plus' }}" aria-hidden="true"></i> <span class="sl-btn-text">{{ $accion }}</span>
        </a>
      </header>

      <div class="sl-toolbar">
        <div class="sl-search">
          <i class="fas fa-search" aria-hidden="true"></i>
          <input type="search" class="form-control" id="id_txt_buscar" placeholder="Buscar por nombre, cédula o teléfono"
                 aria-label="Buscar solicitudes" autocomplete="off" enterkeyhint="search">
        </div>
        <button type="button" class="sl-btn button_export_excel" id="btn-export-gasto" title="Exportar a Excel">
          <i class="far fa-file-excel" aria-hidden="true"></i><span class="sl-btn-text">Excel</span>
        </button>
      </div>

      <div class="sl-list">
        <table id="tbl_ingresos" class="table sl-table" width="100%"></table>
      </div>
    </div>
  </section>

  {{-- ===================== Hoja: elegir cliente a renovar ===================== --}}
  <div class="modal fade sl-modal" id="modal-renovacion" tabindex="-1" aria-labelledby="slRenTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <h2 class="modal-title" id="slRenTitle">Renovar cliente</h2>
            <p class="sl-modal-sub">Elija el cliente inactivo al que se le hará la solicitud de renovación.</p>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="sl-search sl-search--modal">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" class="form-control" id="txt_search_renovaciones" placeholder="Buscar cliente"
                   aria-label="Buscar cliente para renovar" autocomplete="off" enterkeyhint="search">
          </div>
          <table id="tbl_renovaciones" class="table sl-pick" style="width:100%"></table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
