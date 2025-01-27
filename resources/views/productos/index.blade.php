@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Título de la página -->
        <h1 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.8rem; color: #34495e; text-transform: uppercase; border-bottom: 2px solid #2980b9;">
            Productos
        </h1>

        <!-- Mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Barra de búsqueda -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form method="GET" action="{{ route('productos.index') }}" class="d-flex">
                    <input type="text" name="search" placeholder="Buscar producto..." class="form-control form-control-sm rounded-3 shadow-sm px-3 py-2" value="{{ request('search') }}" style="background-color: #f7f7f7; border: 1px solid #ddd;">
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 ms-2" style="height: 40px; padding: 6px 12px;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <!-- Botón Nuevo Producto -->
        <div class="text-end mb-4">
            <button class="btn btn-success btn-sm rounded-3 px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalCreateProducto"><i class="fas fa-plus-circle"></i> Nuevo Producto</button>
        </div>

        <!-- Productos organizados en tarjetas -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($productos as $producto)
                <div class="col">
                    <div class="card shadow-sm border-light rounded-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->nombre }}</h5>
                            <p class="card-text">{{ mb_strimwidth($producto->descripcion, 0, 100, '...') }}</p> <!-- Limitar descripción -->
                            <p class="card-text"><strong>Precio:</strong> S/ {{ number_format($producto->precio, 2) }}</p>
                            <p class="card-text"><strong>Cantidad:</strong> {{ $producto->cantidad }}</p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-warning btn-sm rounded-3" data-bs-toggle="modal" data-bs-target="#modalEditProducto{{ $producto->id }}"><i class="fas fa-edit"></i> Editar</button>
                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-3"><i class="fas fa-trash-alt"></i> Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar producto -->
                <div class="modal fade" id="modalEditProducto{{ $producto->id }}" tabindex="-1" aria-labelledby="modalEditProductoLabel{{ $producto->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditProductoLabel{{ $producto->id }}">Editar Producto</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $producto->nombre }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="descripcion" class="form-label">Descripción</label>
                                        <textarea class="form-control" id="descripcion" name="descripcion" required>{{ $producto->descripcion }}</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="precio" class="form-label">Precio</label>
                                        <input type="number" class="form-control" id="precio" name="precio" value="{{ $producto->precio }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cantidad" class="form-label">Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="{{ $producto->cantidad }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Actualizar Producto</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para crear nuevo producto -->
    <div class="modal fade" id="modalCreateProducto" tabindex="-1" aria-labelledby="modalCreateProductoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateProductoLabel">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('productos.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Crear Producto</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
