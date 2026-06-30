@extends('layouts.lyt_login')
@section('content')

<div class="secure-wrapper">
    <header class="secure-header">
        <div class="secure-header-inner">
            <div class="secure-brand">
                <span class="material-symbols-outlined brand-shield">shield_lock</span>
                <span class="brand-name">CREDIN$TANTES</span>
            </div>
            <div class="secure-header-right">
                <span class="header-badge">USO INTERNO</span>
                <span class="material-symbols-outlined header-lang">language</span>
            </div>
        </div>
    </header>

    <main class="secure-main">
        <div class="secure-card-wrap">
            <div class="secure-card">
                <div class="card-accent"></div>
                <div class="card-body">
                    <div class="card-icon-circle">
                        <img class="card-logo-img" src="{{ asset('img/Logo.png') }}" alt="Credin$tante">
                    </div>
                    <h2 class="card-title">Acceso al Sistema</h2>
                    <p class="card-subtitle">Inicie sesión con sus credenciales de acceso</p>

                    <form id="frm_login" method="POST" action="{{route('login')}}" class="secure-form" autocomplete="off">
                        @csrf

                        <div class="field-group">
                            <label class="field-label" for="lbl_email">Correo</label>
                            <div class="field-input-wrap">
                                <span class="material-symbols-outlined field-icon">mail</span>
                                <input type="email" name="email" class="field-input" id="lbl_email" placeholder="usuario@credinstante.com" value="wilber@credinstantes.com">
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="lbl_pass">Contraseña</label>
                            <div class="field-input-wrap">
                                <span class="material-symbols-outlined field-icon">lock</span>
                                <input type="password" name="password" class="field-input" id="lbl_pass" placeholder="••••••••" value="123456">
                            </div>
                        </div>

                        <button type="submit" class="btn-acceder-secure">
                            Acceder
                        </button>

                        <div class="card-footer-divider">
                            <span class="divider-line"></span>
                            <span class="version-label">{{ENV('APP_VERSION')}}</span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="security-notice">
                <span class="material-symbols-outlined notice-icon">info</span>
                <p class="notice-text">Este es un sistema restringido. El acceso no autorizado está estrictamente prohibido.</p>
            </div>
        </div>
    </main>

    <footer class="secure-footer">
        <div class="secure-footer-inner">
            <div class="footer-left">
                <span class="footer-badge">USO INTERNO</span>
                <span class="footer-divider"></span>
                <p class="footer-copy">© 2024 CREDI$TANTE — Derechos Reservados </p>
            </div>
            <div class="footer-right">
                <a href="#" class="footer-link">
                    <span class="material-symbols-outlined link-icon">policy</span>
                    Política de Seguridad
                </a>
                <span class="version-pill">System {{ENV('APP_VERSION')}}</span>
            </div>
        </div>
    </footer>
</div>

<script>
    document.querySelectorAll('.field-input').forEach(input => {
        input.addEventListener('focus', () => {
            input.closest('.field-input-wrap').classList.add('input-focused');
        });
        input.addEventListener('blur', () => {
            input.closest('.field-input-wrap').classList.remove('input-focused');
        });
    });

    window.history.replaceState(null, null, window.location.href);
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>

@endsection

@section('metodosjs')
@include('jsViews.js_login')
@endsection
