@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Título de la página -->
    <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; font-size: 2.4rem; font-weight: 700; color: #4A4A4A;">Ventas</h1>
    
    <!-- Botón para abrir el modal de nueva venta -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#nuevaVentaModal">Nueva Venta</button>
    
    <!-- Tabla de ventas -->
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
            <tr>
                <td>{{ $venta->codigo }}</td>
                <td>{{ $venta->fecha }}</td>
                <td>{{ $venta->total }}</td>
                <td>
                    @foreach ($venta->productos as $producto)
                        {{ $producto->nombre }} ({{ $producto->pivot->cantidad_vendida }} unidades) <br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal de nueva venta -->
<div class="modal fade" id="nuevaVentaModal" tabindex="-1" role="dialog" aria-labelledby="nuevaVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaVentaModalLabel">Seleccionar Productos para la Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formVenta" action="{{ route('ventas.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="search">Buscar Producto:</label>
                        <input type="text" id="search" class="form-control" placeholder="Buscar productos...">
                    </div>

                    <div id="productosList" class="form-group">
                        <!-- Aquí se agregarán los productos -->
                    </div>

                    <div id="productosSeleccionados">
                        <!-- Los productos seleccionados aparecerán aquí -->
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success">Registrar Venta</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Función para buscar productos y mostrar resultados
    document.getElementById('search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        // Filtrar productos según la búsqueda
        let filteredProducts = @json($productos).filter(function(producto) {
            return producto.nombre.toLowerCase().includes(searchTerm);
        });

        // Mostrar productos en el modal
        const productosList = document.getElementById('productosList');
        productosList.innerHTML = '';
        
        filteredProducts.forEach(producto => {
            const div = document.createElement('div');
            div.classList.add('product-item');
            div.innerHTML = `
                <input type="checkbox" name="productos[${producto.id}][id]" value="${producto.id}" data-nombre="${producto.nombre}" data-precio="${producto.precio}" class="producto-checkbox">
                ${producto.nombre} - S/ ${producto.precio}
                <input type="number" name="productos[${producto.id}][cantidad_vendida]" class="form-control mt-2" min="1" placeholder="Cantidad" required>
            `;
            productosList.appendChild(div);
        });
    });

    // Evento para mostrar los productos seleccionados
    document.getElementById('formVenta').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const selectedProducts = [];
        const checkboxes = document.querySelectorAll('.producto-checkbox:checked');

        checkboxes.forEach(checkbox => {
            const productData = {
                id: checkbox.value,
                nombre: checkbox.getAttribute('data-nombre'),
                precio: checkbox.getAttribute('data-precio'),
                cantidad: checkbox.closest('div').querySelector('input[type="number"]').value
            };
            selectedProducts.push(productData);
        });

        // Mostrar productos seleccionados
        const productosSeleccionadosDiv = document.getElementById('productosSeleccionados');
        productosSeleccionadosDiv.innerHTML = selectedProducts.map(product => `
            <p>${product.nombre} - Cantidad: ${product.cantidad} - S/ ${product.precio} x ${product.cantidad}</p>
        `).join('');

        // Enviar formulario para registrar la venta
        this.submit();
    });
</script>
@endsection
