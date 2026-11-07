# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Credinstantes is a Laravel 7 (PHP 7.4) web app for managing a microcredit portfolio: clients, credits, payments (abonos), cash counts (arqueos), payroll, operating expenses, and reports. The UI, code identifiers, comments, and commit messages are in Spanish. The database is MySQL.

## Commands

```bash
composer install
php artisan serve                      # local dev server (the project also runs under WAMP at E:\Wamp\www)
npm run dev | npm run watch | npm run prod   # Laravel Mix (webpack.mix.js); most frontend assets are prebuilt in public/

./vendor/bin/phpunit                   # tests (only the Laravel example tests exist)
./vendor/bin/phpunit --filter testName

php artisan metricas:calcular          # daily metrics/consolidated snapshot (scheduled 22:00 in app/Console/Kernel.php)
php artisan run:CalcularEstadosCredito # recompute credit states (hits {APP_URL}/api/CalcularEstados over HTTP)
```

Docker deployment: `Dockerfile` builds on `kooldev/php:7.4-nginx-sqlsrv-prod`, and `Docker-compose.yaml` runs the published image `gumadesarrollo/credinstante:<version>` on port 84 with `.env` mounted into the container.

## Architecture

**Classic MVC with fat models.** Controllers are thin. Most business logic, queries, and even HTML generation (DataTables action buttons, badges) live in static methods on `app/Models/*` (e.g. `Credito`, `Abono`, `Arqueo`, `ReportsModels`, `Payroll`). A typical controller action looks like `return response()->json(Model::someStaticMethod($request));`. Add new logic in the model the same way.

**Legacy schema.** Tables do not follow Laravel conventions. Every model sets `$table` (`tbl_*`, `cat_*` catalogs), a custom `$primaryKey` (e.g. `id_creditos`, `id_clientes`), and usually `$timestamps = false`. Several models map to MySQL **views** (`view_fecha_pagos`, `view_status_cliente`, `view_logs_pagos`, `view_creditos_history`, `view_dayslastpayment`, `view_cliente_promotores`) and stored procedures (`CALL CalcConsolidado(?)`, `CALL actualizar_tabla_abonos()`). These are defined in the DB, not in `database/migrations`, and the migrations there are incomplete. Don't assume the migrations reflect the real schema.

**Routing.** Everything is in `routes/web.php` as flat `'Controller@method'` string routes, with no groups or prefixes. The pattern is a GET route that renders a Blade page plus POST routes (`getX`, `SaveX`, `RemoveX`, `ExportX`) called via jQuery AJAX from that page. `routes/api.php` exposes the unauthenticated `CalcularEstados` endpoint used by the credit-state recalculation.

**Auth, roles & access control.**
- Login (`Auth\LoginController@login`) requires `users.activo = 'S'` and `users.Lock = 1`. It stores `rol`, `Zona`, `name_session`, and `name_rol` in the session and writes a `login_log` entry with parsed user-agent/device info.
- Roles (`users.id_rol`): 1 = admin, 2 = cobrador (lands on `Activos/0`), 3 = lands on Dashboard, 4 = promotor, 5 = supervisor. Post-login redirects are in `LoginController::redirectTo` and `RedirectIfAuthenticated`.
- There is no policy/gate layer. Permission checks are inline `in_array(Auth::User()->id_rol, [...])` or `Session::get('rol')`, spread across controllers, models, and Blade views. When changing permissions, grep all three.
- Each controller constructor applies `auth` middleware plus the `App\Traits\CheckUserLock` closure, which logs out users whose `Lock == 0`. New controllers should copy that constructor.
- Data is scoped by zone (`id_zona` / `cat_zona`). Many queries filter by the user's zone unless the user is an admin.

**Daily recalculation.** `DateRecord::Check()` inserts one `tbl_logs` row per day. On the first dashboard load of the day it returns true and renders `Dashboard.update`, which calls `CalcularEstados` via AJAX (`jsViews/js_calc`) to recompute credit states (active/mora/vencido from `EstadosMonitor`), then reloads.

**Views.** Blade pages live under `resources/views/<Module>/` and mostly `@extends('layouts.lyt_listas')` (AdminLTE 3 + jQuery, DataTables, Select2, SweetAlert2, Toastr, daterangepicker, all served from `public/plugins` and `public/js`). Page JavaScript is inline Blade partials in `resources/views/jsViews/js_*.blade.php`, included by each page. AJAX calls pass `_token: "{{ csrf_token() }}"`.

**Exports & PDFs.** Excel exports use `maatwebsite/excel` (classes in `app/Exports`) and, in some older code, `phpoffice/phpexcel`. Vouchers and PDFs use `barryvdh/laravel-dompdf`. Bulk updates use `mavinoo/laravel-batch` (`\Batch::update($model, $rows, $index)`).

**Logging.** Custom channels in `config/logging.php`: `log_vouchers`, `log_general`, `log_calc_Estados`. The log viewer is at `/logs` (rap2hpoutre).

## Conventions

- Commit subject format (see git log): `<emoji> [TAG] Title`, e.g. `✅ [FIX] ...`, `📝 [ADD] ...`, `📝 [EDIT] ...`, `✅ [UPD] ...`.
- Active development happens on the `Features` branch, and `master` is the main branch.
