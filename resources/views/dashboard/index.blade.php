@extends('layouts.app') {{-- o el layout que estés usando --}}

@section('content')
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-chart-pie me-2"></i>Dashboard</h4>

    {{-- MÉTRICAS SUPERIORES --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <p class="mb-1 text-muted">Ventas Totales</p>
                    <h4 class="fw-bold text-success">S/ {{ number_format($ventasTotales, 2) }}</h4>
                    <small class="text-success">+20% este mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <p class="mb-1 text-muted">Productos Vendidos</p>
                    <h4 class="fw-bold">{{ $productosVendidos }}</h4>
                    <small class="text-success">+8 ventas hoy</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <p class="mb-1 text-muted">Clientes Registrados</p>
                    <h4 class="fw-bold">{{ $clientesRegistrados }}</h4>
                    <small class="text-success">+12 este mes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <p class="mb-1 text-muted">Reservas Hoy</p>
                    <h4 class="fw-bold">{{ $reservasHoy }}</h4>
                    <small class="text-warning">{{ $reservasPendientes }} pendientes</small>
                </div>
            </div>
        </div>
    </div>

    {{-- RANKING & ALERTAS --}}
    <div class="row">
        {{-- Ranking de Productos Más Vendidos --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-chart-line me-2"></i>Ranking de Productos Más Vendidos
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($ranking as $index => $producto)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-dark rounded-pill me-2">{{ $index + 1 }}</span>
                                <strong>{{ $producto->nombre }}</strong>
                                <br>
                                <small>{{ $producto->total_ventas }} ventas | Stock: {{ $producto->cantidad }}</small>
                            </div>
                            @if($producto->cantidad <= 10)
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            @endif
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No hay productos vendidos.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Alertas de Stock --}}
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="fas fa-boxes me-2"></i>Alertas de Stock
                </div>
                <div class="card-body">
                    @if($productosStockBajo->count())
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>{{ $productosStockBajo->count() }} productos necesitan reposición</div>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($productosStockBajo as $producto)
                                <li class="list-group-item d-flex justify-content-between">
                                    {{ $producto->nombre }}
                                    <span class="text-danger fw-bold">{{ $producto->cantidad }} unidades</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-success">
                            Todos los productos tienen stock suficiente.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
