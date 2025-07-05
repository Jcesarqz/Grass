@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="text-center mb-4">Gestión de Clientes</h1>

    <!-- Botón -->
    <div class="text-end mb-3">
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCreateCliente">
            <i class="bi bi-plus-circle"></i> Registrar Cliente
        </button>
    </div>
<div class="row row-cols-1 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card shadow-sm border rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Total Clientes</small>
                    <h4 class="mb-0 fw-bold">{{ $totalClientes }}</h4>
                </div>
                <i class="bi bi-people fs-4 text-muted"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow-sm border rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Clientes VIP</small>
                    <h4 class="mb-0 fw-bold">{{ $clientesVip }}</h4>
                </div>
                <i class="bi bi-star fs-4 text-muted"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow-sm border rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Puntos Totales</small>
                    <h4 class="mb-0 fw-bold">{{ $puntosTotales }}</h4>
                </div>
                <i class="bi bi-star fs-4 text-muted"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow-sm border rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Compras Totales</small>
                    <h4 class="mb-0 fw-bold">S/ {{ number_format($comprasTotales, 2) }}</h4>
                </div>
                <i class="bi bi-cash-coin fs-4 text-muted"></i>
            </div>
        </div>
    </div>
</div>

    <!-- Tab    la -->
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>DNI</th>
                        <th>Nombre</th>
                        <th>Puntos</th>
                        <th>Total Compras</th>
                        <th>VIP</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->dni }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->puntos }}</td>
                        <td>S/ {{ number_format($cliente->total_compras, 2) }}</td>
                        <td>
                            @if($cliente->vip)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditCliente{{ $cliente->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar este cliente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Editar Cliente -->
                    <div class="modal fade" id="modalEditCliente{{ $cliente->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('clientes.update', $cliente) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Editar Cliente</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>DNI</label>
                                            <input type="text" name="dni" id="dni-edit-{{ $cliente->id }}" class="form-control" maxlength="8" value="{{ $cliente->dni }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Nombre</label>
                                            <input type="text" name="nombre" id="nombre-edit-{{ $cliente->id }}" class="form-control" value="{{ $cliente->nombre }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Puntos</label>
                                            <input type="number" name="puntos" class="form-control" value="{{ $cliente->puntos }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Total de Compras</label>
                                            <input type="number" name="total_compras" step="0.01" class="form-control" value="{{ $cliente->total_compras }}" required>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox" name="vip" class="form-check-input" id="vip-edit-{{ $cliente->id }}" {{ $cliente->vip ? 'checked' : '' }}>
                                            <label class="form-check-label" for="vip-edit-{{ $cliente->id }}">Cliente VIP</label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Actualizar</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Script para autocompletar en edición -->
                    <script>
                        document.getElementById('dni-edit-{{ $cliente->id }}').addEventListener('keyup', function () {
                            const dni = this.value;
                            if (dni.length === 8) {
                                fetch(`https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImpmY2M5NTAxMjMwOUBnbWFpbC5jb20ifQ.UaK6eecpbt-mVnF9hI-BYSHtl6QQ5hCLU1MNItWe9P8`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (!data.success === false) {
                                            document.getElementById('nombre-edit-{{ $cliente->id }}').value = data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno;
                                        }
                                    });
                            }
                        });
                    </script>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear Cliente -->
<div class="modal fade" id="modalCreateCliente" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>DNI</label>
                        <input type="text" name="dni" id="dni-create" class="form-control" maxlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="nombre-create" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Puntos</label>
                        <input type="number" name="puntos" class="form-control" value="0" required>
                    </div>
                    <div class="mb-3">
                        <label>Total de Compras</label>
                        <input type="number" name="total_compras" step="0.01" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="vip" class="form-check-input" id="vip-create">
                        <label for="vip-create" class="form-check-label">Cliente VIP</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para autocompletar en creación -->
<script>
    document.getElementById('dni-create').addEventListener('keyup', function () {
        const dni = this.value;
        if (dni.length === 8) {
            fetch(`https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImpmY2M5NTAxMjMwOUBnbWFpbC5jb20ifQ.UaK6eecpbt-mVnF9hI-BYSHtl6QQ5hCLU1MNItWe9P8`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success === false) {
                        document.getElementById('nombre-create').value = data.nombres + ' ' + data.apellidoPaterno + ' ' + data.apellidoMaterno;
                    }
                });
        }
    });
</script>
@endsection
