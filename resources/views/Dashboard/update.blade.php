@extends('layouts.lyt_listas')
@section('metodosjs')
@include('jsViews.js_calc')
@endsection
@section('content')
<div class="wrapper">

@include('layouts.lyt_aside')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Calculando Morosidad & Vencimiento...</h3>
                    </div>
                    <div class="card-body text-center" >
                        <img src="{{ asset('img/loading.gif') }}"  width="100" height="100" >
                    </div>
                </div>
            </div>
        </section>
    </div>


@include('layouts.lyt_footer')
</div>
@endsection