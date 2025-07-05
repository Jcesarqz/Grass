@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Registrar Cliente</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" id="dni" class="form-control" required maxlength="8">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="puntos" class="form-label">Puntos</label>
            <input type="number" name="puntos" class="form-control" value="0" required>
        </div>

        <div class="mb-3">
            <label for="total_compras" class="form-label">Total de Compras</label>
            <input type="number" name="total_compras" step="0.01" class="form-control" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="vip" id="vip" class="form-check-input">
            <label for="vip" class="form-check-label">Cliente VIP</label>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cliente</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>

{{-- JavaScript para autocompletar nombre con la API --}}
<script>
    document.getElementById('dni').addEventListener('keyup', function () {
        const dni = this.value;
        if (dni.length === 8) {
            fetch(`https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImpmY2M5NTAxMjMwOUBnbWFpbC5jb20ifQ.UaK6eecpbt-mVnF9hI-BYSHtl6QQ5hCLU1MNItWe9P8`)
                .then(response => response.json())
                .then(data => {
                    if (data.success !== false) {
                        document.getElementById('nombre').value = data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno;
                    } else {
                        alert('DNI no encontrado');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });