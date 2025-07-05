@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Carrito de Compras</h1>

        <!-- Mostrar los productos en el carrito -->
        @if(session('cart'))
            <ul>
                @foreach(session('cart') as $id => $producto)
                    <li>
                        {{ $producto['nombre'] }} - S/ {{ number_format($producto['precio'], 2) }} 
                        x 
                        <form action="{{ route('ventas.updateCart', $id) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="cantidad" value="{{ $producto['cantidad'] }}" min="1" required>
                            <button type="submit" class="btn btn-warning">Actualizar Cantidad</button>
                        </form>
                        = S/ {{ number_format($producto['total'], 2) }}
                    </li>
                @endforeach
            </ul>

            <p><strong>Total: S/ {{ number_format($total, 2) }}</strong></p>

            <form action="{{ route('ventas.store') }}" method="POST">
                @csrf
                <input type="hidden" name="total" value="{{ $total }}">
                
                <div class="form-group mb-3">
                <label for="cliente_id">Seleccionar Cliente</label>
                <select name="cliente_id" class="form-control" required>
                    <option value="">-- Seleccione un cliente --</option>
                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id }}">
                            {{ $cliente->nombre }} - {{ $cliente->dni }}
                        </option>
                    @endforeach
                </select>
            </div>

                <button type="submit" class="btn btn-success">Realizar Venta</button>
            </form>
        @else
            <p>No hay productos en el carrito.</p>
        @endif
    </div>
@endsection
