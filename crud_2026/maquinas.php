<?php
require_once 'Controladores/CTR_Maquina.php';
/* Ejecutar acciones del controlador (Guardar/Eliminar)*/
$controlador = new CTR_Maquina();
$controlador->procesarAccion();

/*Obtener datos para la vista*/
$maquinas = MDL_Maquina::obtenerTodas();

/*Cargar datos si se presiona editar*/
$editData = ['id' => '', 'tipo_vehiculo' => '', 'marca' => '', 'color' => '', 'modelo' => 'deportivo'];
if (isset($_GET['editar'])) {
    $resultado = MDL_Maquina::obtenerPorId($_GET['editar']);
    if ($resultado) {
        $editData = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Vehiculos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Vehiculos</h2>
        <a href="admin.php" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>
    <hr>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Formulario -->
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h4><?= !empty($editData['id']) ? 'Modificar Vehiculo' : 'Crear vehiculo' ?></h4>
                <form action="maquinas.php" method="post">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label">tipo de vehiculo</label>
                        <select class="form-control" name="tipo_vehiculo" id="tipo_vehiculo">
                            <option value="moto" <?= $editData['tipo_vehiculo'] === 'moto' ? 'selected' : '' ?>>moto
                            </option>
                            <option value="auto" <?= $editData['tipo_vehiculo'] === 'auto' ? 'selected' : '' ?>>auto
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">marca</label>
                        <input type="text" class="form-control" name="marca"
                            value="<?= htmlspecialchars($editData['marca']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">color</label>
                        <textarea class="form-control"
                            name="color"><?= htmlspecialchars($editData['color']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">modelo</label>
                        <select class="form-control" name="modelo" id="modelo"
                            data-modelo-actual="<?= htmlspecialchars($editData['modelo']) ?>">
                        </select>
                    </div>

                    <button type="submit"
                        class="btn btn-primary w-100"><?= !empty($editData['id']) ? 'Actualizar' : 'Guardar' ?></button>

                    <?php if (!empty($editData['id'])): ?>
                        <a href="maquinas.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Tabla -->
        <div class="col-md-8">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>tipo_vehiculo</th>
                        <th>marca</th>
                        <th>color</th>
                        <th>modelo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($maquinas)): ?>
                        <?php foreach ($maquinas as $m): ?>
                            <tr>
                                <td><?= $m['id'] ?></td>
                                <td>
                                    <span class="badge <?= $m['tipo_vehiculo'] === 'moto' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= htmlspecialchars($m['tipo_vehiculo']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($m['marca']) ?></td>
                                <td><?= htmlspecialchars($m['color']) ?></td>
                                <td>
                                    <span class="badge <?= $m['modelo'] === 'deportivo' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= htmlspecialchars($m['modelo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="maquinas.php?editar=<?= $m['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="maquinas.php?eliminar=<?= $m['id'] ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm(' ¿Estás seguro?')">Eliminar</a>
                                    </td>
                                    </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay vehiculos registrados.</td>
                                </tr>
                                    <?php endif; ?>
                            </tbody>
                            </table>
                    </div>
            </div>
            <script>
                const tipoVehiculo = document.getElementById('tipo_vehiculo');
                const modelo = document.getElementById('modelo');

                function actualizarModelos(modeloInicial = '') {
                    const opciones = tipoVehiculo.value === 'moto'
                        ? ['deportiva', 'enduro']
                        : ['deportivo', 'familiar'];

                    modelo.replaceChildren();

                    opciones.forEach(opcion => {
                        const elemento = document.createElement('option');
                        elemento.value = opcion;
                        elemento.textContent = opcion;
                        modelo.appendChild(elemento);
                    });

                    modelo.value = opciones.includes(modeloInicial) ? modeloInicial : opciones[0];
                }

                actualizarModelos(modelo.dataset.modeloActual);

                tipoVehiculo.addEventListener('change', () => actualizarModelos());
            </script>
</body>

</html>