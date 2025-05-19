<?php
// Incluimos el controlador y obtenemos los productos
require_once __DIR__.'/../../controllers/Admin/ClientsController.php';

$controller = new ClientsController();

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'delete':
        if (isset($_GET['id'])) {
            $controller->deleteClient($_GET['id']);
            header('Location: clients-admin.php?success=delete');
            exit;
        }
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->addClient($_POST['cedula'], $_POST['nombre'], $_POST['dirección'], $_POST['telefono'], $_POST['membresia']);
            header('Location: clients-admin.php?success=add');
            exit;
        }
        break;

    default:
        $clientes = $controller->getclientss();
        break;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Garage Barki</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/icons/Logo - Garage Barki.webp" type="image/x-icon">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../../public/assets/css/admin-styles.css">
    <link rel="shortcut icon" href="../../../public/assets/icons/Logo - Garage Barki.webp" type="image/x-icon">
</head>
<body>

        <nav class="sidebar" id="sidebar">
        <div class="sidebar-sticky">
            <div class="sidebar-header">
                <h3>GARAGE<span>BARKI</span></h3>
                <p class="mb-0">Panel de Administración</p>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tachometer-alt"></i>
                        Inicio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tshirt"></i>
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-shopping-cart"></i>
                        Proveedores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/app/views/admin/clients-admin.php">
                        <i class="fas fa-users"></i>
                        Clientes
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="display-6 fw-bold text-dark">Clientes</h1>
            </div>
            <button class="btn btn-primary rounded-pill px-4 me-3" data-bs-toggle="modal" data-bs-target="#addclient$clientModal">
                <i class="fas fa-plus me-1"></i> Añadir Cliente
            </button>
            

            <!-- Mensajes de éxito/error -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success mt-3">
                    <?php 
                    switch($_GET['success']) {
                        case 'add': echo 'Cliente agregado correctamente'; break;
                        case 'delete': echo 'Cliente eliminado correctamente'; break;
                    }
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mt-3">
                    <?php 
                    if ($_GET['error'] === 'cedula_duplicada') {
            $cedula = isset($_GET['cedula']) ? htmlspecialchars($_GET['cedula']) : '';
            echo "Error: La cédula $cedula ya está registrada.";
                    }
                    ?>
    </div>
<?php endif; ?>

            <!-- Tabla de Clientes -->
            <div class="card mt-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th class="text-center">Cédula</th>
            <th>Nombre</th>
            <th>Dirección</th>
            <th class="text-end">Teléfono</th>
            <th class="text-center">Membresía</th>
            <th class="text-center">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($clientss)): ?>
            <?php foreach ($clientss as $client): ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($client['cedula'] ?? '') ?></td>
                    <td><?= htmlspecialchars($client['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($client['direccion'] ?? '') ?></td>
                    <td class="text-end"><?= preg_replace('/(\d{4})(\d{7})/', '$1-$2', $client['telefono'] ?? '') ?></td>
                    <td class="text-center"><?= htmlspecialchars($client['membresia'] ?? '') ?></td>
                    <td class="text-center">
                        <a href="clients-admin.php?action=delete&cedula=<?= $client['cedula'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('¿Estás seguro de eliminar a este cliente?')">
                            <i class="fas fa-trash"></i> Eliminar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">
                    <div class="alert alert-info mb-0">No hay clientes disponibles</div>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Añadir Clientes -->
    <div class="modal fade" id="addclient$clientModal" tabindex="-1" aria-labelledby="addclient$clientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addclient$clientModalLabel">Añadir Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="clients-admin.php?action=add" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Cédula</label>
                            <input type="text" 
                                class="form-control" 
                                name="cedula" 
                                placeholder="Ej: V30803977" 
                                pattern="\d{6,8}" 
                                maxlength="8" minlength="6"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,8);"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" 
                                name="nombre" 
                                placeholder="Ingrese su nombre completo" 
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$"
                                oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Ej: Av. Leones, Edif. Los Leones, Piso 3, Barquisimeto" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" 
                                class="form-control" 
                                name="telefono" 
                                placeholder="Ej: 04245555555" 
                                pattern="\d{11}" 
                                maxlength="11" minlength="11"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11);"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Membresía</label>
                            <select class="form-select" name="membresia" required>
                                <option value="regular">Regular</option>
                                <option value="vip">VIP</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>