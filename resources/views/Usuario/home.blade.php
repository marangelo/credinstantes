@extends('layouts.lyt_login')
@section('content')
@php
  $loginError = $errors->first('email') ?: $errors->first('password');
@endphp

<div class="lg">
    {{-- Franja / panel de marca: mismo color que el menú lateral del sistema --}}
    <header class="lg-hero">
        <div class="lg-hero-inner">
            <img class="lg-logo" src="{{ asset('img/Logo.png') }}" alt="" width="76" height="76">
            <p class="lg-brand">CREDIN$TANTE</p>
            <p class="lg-slogan">¡Creciendo con vos!</p>
            <span class="lg-pill"><i class="fas fa-shield-alt" aria-hidden="true"></i> Uso interno</span>
        </div>
    </header>

    {{-- Hoja con el formulario --}}
    <main class="lg-sheet">
        <div class="lg-card">
            <h1 class="lg-title">Iniciar sesión</h1>

            @if ($loginError)
            <div class="lg-alert" role="alert" id="loginError">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <span>{{ $loginError }}</span>
            </div>
            @endif

            <form id="frm_login" method="POST" action="{{ route('login') }}" class="lg-form" novalidate>
                @csrf

                <div class="lg-field">
                    <label class="lg-label" for="lbl_email">Correo</label>
                    <div class="lg-input {{ $loginError ? 'is-invalid' : '' }}">
                        <i class="far fa-envelope lg-input-icon" aria-hidden="true"></i>
                        <input type="email" name="email" id="lbl_email"
                               placeholder="usuario@credinstante.com" value="{{ old('email', 'wilber@credinstantes.com') }}"
                               autocomplete="username" inputmode="email" autocapitalize="off" spellcheck="false"
                               required {{ $loginError ? 'aria-invalid=true aria-describedby=loginError' : 'autofocus' }}>
                    </div>
                </div>

                <div class="lg-field">
                    <label class="lg-label" for="lbl_pass">Contraseña</label>
                    <div class="lg-input {{ $loginError ? 'is-invalid' : '' }}">
                        <i class="fas fa-lock lg-input-icon" aria-hidden="true"></i>
                        <input type="password" name="password" id="lbl_pass"
                               placeholder="••••••••" value="123456" autocomplete="current-password" required
                               aria-describedby="capsHint" {{ $loginError ? 'autofocus' : '' }}>
                        <button type="button" class="lg-toggle" id="btnTogglePass"
                                aria-label="Mostrar contraseña" aria-pressed="false" aria-controls="lbl_pass">
                            <i class="far fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>
                    <p class="lg-hint" id="capsHint" hidden>
                        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                        Bloq Mayús está activado
                    </p>
                </div>

                <button type="submit" class="lg-submit" id="btnAcceder" aria-busy="false">
                    <span class="lg-spinner" aria-hidden="true"></span>
                    <span class="btn-label">Acceder</span>
                </button>

                <input type="hidden" name="device_model"      id="device_model">
                <input type="hidden" name="device_brand"      id="device_brand">
                <input type="hidden" name="device_device"     id="device_device">
                <input type="hidden" name="device_android_id" id="device_android_id">
            </form>

            <p class="lg-notice">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
                Este es un sistema restringido. El acceso no autorizado está estrictamente prohibido.
            </p>
        </div>

        <footer class="lg-foot">
            <span>© {{ date('Y') }} CREDIN$TANTE</span>
            <span aria-hidden="true">·</span>
            <span>Versión {{ ENV('APP_VERSION') }}</span>
        </footer>
    </main>
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
        toggle.querySelector('i').className = show ? 'far fa-eye-slash' : 'far fa-eye';
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
