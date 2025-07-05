@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Cliente</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" id="dni" class="form-control" value="{{ $cliente->dni }}" required maxlength="8">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $cliente->nombre }}" required>
        </div>

        <div class="mb-3">
            <label for="puntos" class="form-label">Puntos</label>
            <input type="number" name="puntos" class="form-control" value="{{ $cliente->puntos }}" required>
        </div>

        <div class="mb-3">
            <label for="total_compras" class="form-label">Total de Compras</label>
            <input type="number" name="total_compras" step="0.01" class="form-control" value="{{ $cliente->total_compras }}" required>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="vip" id="vip" class="form-check-input" {{ $cliente->vip ? 'checked' : '' }}>
            <label for="vip" class="form-check-label">Cliente VIP</label>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
