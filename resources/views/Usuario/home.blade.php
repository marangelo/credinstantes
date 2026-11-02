@extends('layouts.lyt_login')
@section('content')
@php
  $loginError = $errors->first('email') ?: $errors->first('password');
@endphp

<div class="secure-wrapper">
    <header class="secure-header">
        <div class="secure-header-inner">
            <div class="secure-brand">
                <span class="material-symbols-outlined brand-shield" aria-hidden="true">shield_lock</span>
                <span class="brand-name">CREDIN$TANTE</span>
            </div>
            <span class="header-badge">Uso interno</span>
        </div>
    </header>

    <main class="secure-main">
        <div class="secure-card-wrap">
            <div class="secure-card">
                <div class="card-accent" aria-hidden="true"></div>
                <div class="card-body">
                    <img class="card-logo-img" src="{{ asset('img/Logo.png') }}" alt="" width="88" height="88">

                    <div class="card-heading">
                        <h1 class="card-title">CREDIN$TANTE</h1>
                        <p class="card-subtitle">¡Creciendo con vos!</p>
                    </div>

                    @if ($loginError)
                    <div class="form-alert" role="alert" id="loginError">
                        <span class="material-symbols-outlined" aria-hidden="true">error</span>
                        <span>{{ $loginError }}</span>
                    </div>
                    @endif

                    <form id="frm_login" method="POST" action="{{ route('login') }}" class="secure-form" novalidate>
                        @csrf

                        <div class="field-group">
                            <label class="field-label" for="lbl_email">Correo</label>
                            <div class="field-input-wrap {{ $loginError ? 'is-invalid' : '' }}">
                                <span class="material-symbols-outlined field-icon" aria-hidden="true">mail</span>
                                <input type="email" name="email" class="field-input" id="lbl_email"
                                       placeholder="usuario@credinstante.com" value="{{ old('email', 'wilber@credinstantes.com') }}"
                                       autocomplete="username" inputmode="email" autocapitalize="off" spellcheck="false"
                                       required {{ $loginError ? 'aria-invalid=true aria-describedby=loginError' : 'autofocus' }}>
                            </div>
                        </div>

                        <div class="field-group">
                            <label class="field-label" for="lbl_pass">Contraseña</label>
                            <div class="field-input-wrap {{ $loginError ? 'is-invalid' : '' }}">
                                <span class="material-symbols-outlined field-icon" aria-hidden="true">lock</span>
                                <input type="password" name="password" class="field-input" id="lbl_pass"
                                       placeholder="••••••••" value="123456" autocomplete="current-password" required
                                       aria-describedby="capsHint" {{ $loginError ? 'autofocus' : '' }}>
                                <button type="button" class="field-toggle" id="btnTogglePass"
                                        aria-label="Mostrar contraseña" aria-pressed="false" aria-controls="lbl_pass">
                                    <span class="material-symbols-outlined" aria-hidden="true">visibility</span>
                                </button>
                            </div>
                            <p class="field-hint" id="capsHint" hidden>
                                <span class="material-symbols-outlined" aria-hidden="true">keyboard_capslock</span>
                                Bloq Mayús está activado
                            </p>
                        </div>

                        <button type="submit" class="btn-acceder-secure" id="btnAcceder" aria-busy="false">
                            <span class="btn-spinner" aria-hidden="true"></span>
                            <span class="btn-label">Acceder</span>
                        </button>

                        <input type="hidden" name="device_model"      id="device_model">
                        <input type="hidden" name="device_brand"      id="device_brand">
                        <input type="hidden" name="device_device"     id="device_device">
                        <input type="hidden" name="device_android_id" id="device_android_id">
                    </form>
                </div>
            </div>

            <div class="security-notice">
                <span class="material-symbols-outlined notice-icon" aria-hidden="true">info</span>
                <p class="notice-text">Este es un sistema restringido. El acceso no autorizado está estrictamente prohibido.</p>
            </div>
        </div>
    </main>

    <footer class="secure-footer">
        <div class="secure-footer-inner">
            <p class="footer-copy">© {{ date('Y') }} CREDIN$TANTE — Derechos reservados</p>
            <span class="version-pill">Versión {{ ENV('APP_VERSION') }}</span>
        </div>
    </footer>
</div>

<script>
(function () {
    var form   = document.getElementById('frm_login');
    var email  = document.getElementById('lbl_email');
    var pass   = document.getElementById('lbl_pass');
    var toggle = document.getElementById('btnTogglePass');
    var caps   = document.getElementById('capsHint');
    var btn    = document.getElementById('btnAcceder');

    // Datos del dispositivo (inyectados por la app Android). Se leen también al enviar,
    // por si el usuario envía antes de que termine la inyección.
    function fillDevice() {
        document.getElementById('device_model').value      = window.deviceModel || '';
        document.getElementById('device_brand').value      = window.deviceBrand || '';
        document.getElementById('device_device').value     = window.deviceDevice || '';
        document.getElementById('device_android_id').value = window.deviceAndroidId || '';
    }
    document.addEventListener('DOMContentLoaded', function () { setTimeout(fillDevice, 500); });

    // Mostrar / ocultar contraseña
    toggle.addEventListener('click', function () {
        var show = pass.type === 'password';
        pass.type = show ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
        toggle.setAttribute('aria-label', show ? 'Ocultar contraseña' : 'Mostrar contraseña');
        toggle.querySelector('.material-symbols-outlined').textContent = show ? 'visibility_off' : 'visibility';
        pass.focus();
    });

    // Aviso de Bloq Mayús
    function checkCaps(e) {
        if (e.getModifierState) caps.hidden = !e.getModifierState('CapsLock');
    }
    pass.addEventListener('keydown', checkCaps);
    pass.addEventListener('keyup', checkCaps);
    pass.addEventListener('blur', function () { caps.hidden = true; });

    // Validación mínima en el cliente + estado de envío (evita doble clic)
    form.addEventListener('submit', function (e) {
        if (!email.value.trim()) { e.preventDefault(); email.focus(); return; }
        if (!pass.value)         { e.preventDefault(); pass.focus();  return; }
        fillDevice();
        btn.disabled = true;
        btn.setAttribute('aria-busy', 'true');
        btn.querySelector('.btn-label').textContent = 'Accediendo…';
    });

    // Al volver con "atrás", recargar para no mostrar un formulario con estado viejo
    window.history.replaceState(null, null, window.location.href);
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) window.location.reload();
    });
})();
</script>
@endsection
