@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h1 class="display-4 font-weight-bold text-primary">Gestión de Ventas</h1>
    </div>

    <div class="d-flex justify-content-center mb-4">
        <form method="GET" action="{{ route('ventas.index') }}" class="w-100">
            <div class="input-group input-group-lg">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Buscar productos..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($productos as $producto)
            <div class="col">
                <div class="card shadow-sm border-0 rounded-4 hover-zoom">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text text-muted">{{ $producto->descripcion }}</p>
                        <p class="card-text text-success font-weight-bold fs-4">S/ {{ number_format($producto->precio, 2) }}</p>
                        <p class="card-text text-muted">Cantidad disponible: {{ $producto->cantidad }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <form action="{{ route('ventas.addToCart', $producto->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="cantidad" value="1">
                                <button type="submit" class="btn btn-primary">Añadir al Carrito</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $productos->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>

    <div class="position-fixed bottom-0 end-0 p-3">
        <button type="button" class="btn btn-success btn-lg rounded-circle" data-bs-toggle="modal" data-bs-target="#cartModal">
            <i class="fas fa-shopping-cart"></i>
        </button>
    </div>

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Carrito de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if(session('cart') && count(session('cart')) > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(session('cart') as $id => $producto)
                                    <tr>
                                        <td>{{ $producto['nombre'] }}</td>
                                        <td>S/ {{ number_format($producto['precio'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('ventas.updateCart', $id) }}" method="POST">
                                                @csrf
                                                <input type="number" name="cantidad[{{ $id }}]" value="{{ $producto['cantidad'] }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                                                <button type="submit" class="btn btn-warning btn-sm mt-2">Actualizar</button>
                                            </form>
                                        </td>
                                        <td>S/ {{ number_format($producto['total'], 2) }}</td>
                                        <td>
                                            <form action="{{ route('ventas.removeFromCart', $id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end">
                            <strong>Total: S/ {{ number_format(array_sum(array_column(session('cart'), 'total')), 2) }}</strong>
                        </div>

                        <form action="{{ route('ventas.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="total" value="{{ array_sum(array_column(session('cart'), 'total')) }}">
                            <button type="submit" class="btn btn-success btn-block mt-3">Realizar Venta</button>
                        </form>
                    @else
                        <p class="text-center text-muted">No hay productos en el carrito.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="h4 font-weight-bold text-secondary">Historial de Ventas</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Código</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Total</th>
                        <th>Productos Vendidos</th>
                        <th>Cliente</th> {{-- Nueva columna --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                        <tr>
                            <td>{{ $venta->codigo }}</td>
                            <td>{{ $venta->fecha->format('d/m/Y') }}</td>
                            <td>{{ $venta->fecha->format('H:i:s') }}</td>
                            <td>S/ {{ number_format($venta->total, 2) }}</td>
                            <td>
                                <ul class="list-unstyled">
                                    @foreach($venta->productos as $producto)
                                        <li>{{ $producto->nombre }} ({{ $producto->pivot->cantidad_vendida }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                @if($venta->cliente)
                                    <span class="badge bg-success">{{ $venta->cliente->nombre }}</span>
                                @else
                                    <form action="{{ route('ventas.asignarCliente', $venta->id) }}" method="POST" class="d-flex">
                                        @csrf
                                        <select name="cliente_id" class="form-select form-select-sm me-2" required>
                                            <option value="">Seleccionar cliente</option>
                                            @foreach($clientes as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }} - DNI {{ $cliente->dni }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary">Asignar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $ventas->appends(request()->query())->links() }}
    </div>
</div>
@endsection
