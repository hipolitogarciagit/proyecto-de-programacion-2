<?php
// Inicia la sesion para verificar si el usuario ingreso al sistema.
session_start();

// Si no existe el usuario en sesion, redirige al inicio de sesion.
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Guarda si el usuario tiene permisos de administrador.
$esAdmin = ($_SESSION['rol'] ?? '') === 'admin';
// Carga el controlador encargado de las acciones de vehiculos.
require_once 'Controladores/CTR_Maquina.php';
// Ejecuta el guardado, la modificacion o la eliminacion solicitada.
$controlador = new CTR_Maquina();
$controlador->procesarAccion();

// Obtiene todas las maquinas para mostrarlas en la tabla.
$maquinas = MDL_Maquina::obtenerTodas();

// Define los datos vacios que usa el formulario al crear un vehiculo.
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

    <!-- Muestra el encabezado y el enlace para volver al panel. -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Vehiculos</h2>
        <a href="admin.php" class="btn btn-outline-secondary btn-sm">Volver al Panel</a>
    </div>
    <hr>

    <?php if (!empty($_GET['msg'])): ?>
        <!-- Presenta el mensaje enviado despues de una accion. -->
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php endif; ?>

    <div class="row">
        <?php if ($esAdmin): ?>
            <!-- Muestra el formulario solo para usuarios administradores. -->
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h4><?= !empty($editData['id']) ? 'Modificar Vehiculo' : 'Crear vehiculo' ?></h4>
                    <form action="maquinas.php" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars((string) $editData['id']) ?>">

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
        <?php endif; ?>

        <!-- Muestra la tabla con todos los vehiculos registrados. -->
        <div class="<?= $esAdmin ? 'col-md-8' : 'col-md-12' ?>">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>tipo_vehiculo</th>
                        <th>marca</th>
                        <th>color</th>
                        <th>modelo</th>
                        <?php if ($esAdmin): ?>
                            <th>Acciones</th>
                        <?php endif; ?>
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
                                <?php if ($esAdmin): ?>
                                    <td>
                                        <a href="maquinas.php?editar=<?= $m['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="maquinas.php?eliminar=<?= $m['id'] ?>" class="btn btn-danger btn-sm"
                                            onclick="return confirm(' ¿Estás seguro?')">Eliminar</a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?= $esAdmin ? 6 : 5 ?>" class="text-center text-muted">No hay vehiculos registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
        <?php if ($esAdmin): ?>
            <script>
            // Obtiene los controles del tipo de vehiculo y el modelo.
            const tipoVehiculo = document.getElementById('tipo_vehiculo');
            const modelo = document.getElementById('modelo');

            // Actualiza las opciones disponibles segun el tipo seleccionado.
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

            // Carga los modelos al abrir la pagina y escucha cambios futuros.
            actualizarModelos(modelo.dataset.modeloActual);

            tipoVehiculo.addEventListener('change', () => actualizarModelos());
            </script>
        <?php endif; ?>
</body>

</html>