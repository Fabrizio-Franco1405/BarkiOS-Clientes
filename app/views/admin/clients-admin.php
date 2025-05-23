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
                    <a class="nav-link active" href="./clients-admin.php">
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
            <button class="btn btn-primary rounded-pill px-4 me-3" data-bs-toggle="modal" data-bs-target="#addClientModal">
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
                <table class="table table-hover align-middle" id="clientesTable">
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
                    <tbody id="clientesTableBody">
                        <?php if (!empty($clientss)): ?>
                            <?php foreach ($clientss as $client): ?>
                                <tr id="cliente-<?= htmlspecialchars($client['cedula']) ?>">
                                    <td class="text-center"><?= htmlspecialchars($client['cedula'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($client['nombre'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($client['direccion'] ?? '') ?></td>
                                    <td class="text-end"><?= preg_replace('/(\d{4})(\d{7})/', '$1-$2', $client['telefono'] ?? '') ?></td>
                                    <td class="text-center"><?= htmlspecialchars($client['membresia'] ?? '') ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                                data-cedula="<?= htmlspecialchars($client['cedula']) ?>"
                                                data-nombre="<?= htmlspecialchars($client['nombre']) ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="no-clientes">
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
    <div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientModalLabel">Añadir Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAgregarCliente">
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
    
    

    <!-- Bootstrap JS Bundle with Popper -->
    <!-- SweetAlert2 para alertas bonitas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script para manejar AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tooltips de Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Inicializar el modal
            var addClientModal = document.getElementById('addClientModal');
            if (addClientModal) {
                addClientModal.addEventListener('hidden.bs.modal', function () {
                    // Limpiar el formulario cuando se cierre el modal
                    var form = document.getElementById('formAgregarCliente');
                    if (form) {
                        form.reset();
                        // Limpiar mensajes de error
                        var errorElements = form.querySelectorAll('.is-invalid');
                        errorElements.forEach(function(element) {
                            element.classList.remove('is-invalid');
                        });
                        var errorMessages = form.querySelectorAll('.invalid-feedback');
                        errorMessages.forEach(function(element) {
                            element.remove();
                        });
                    }
                });
            }
            // Agregar cliente con AJAX
            const formAgregar = document.getElementById('formAgregarCliente');
            if (formAgregar) {
                formAgregar.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Limpiar errores anteriores
                    const errorMessages = formAgregar.querySelectorAll('.invalid-feedback');
                    errorMessages.forEach(el => el.remove());
                    const invalidInputs = formAgregar.querySelectorAll('.is-invalid');
                    invalidInputs.forEach(el => el.classList.remove('is-invalid'));
                    
                    const formData = new FormData(this);
                    
                    // Validación básica del lado del cliente
                    let hasErrors = false;
                    const requiredFields = formAgregar.querySelectorAll('[required]');
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = 'Este campo es obligatorio';
                            field.parentNode.appendChild(errorDiv);
                            hasErrors = true;
                        }
                    });
                    
                    if (hasErrors) {
                        return; // Detener el envío si hay errores de validación
                    }
                    
                    // Mostrar loading
                    const submitButton = formAgregar.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';
                    
                    fetch('clients-admin.php?action=add', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la red');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Cerrar el modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addClientModal'));
                            modal.hide();
                            
                            // Mostrar mensaje de éxito
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                            // Agregar la nueva fila a la tabla
                            agregarFilaCliente(data.cliente);
                        } else {
                            throw new Error(data.message || 'Error al procesar la solicitud');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'Ocurrió un error al procesar la solicitud';
                        
                        if (error.message.includes('Failed to fetch')) {
                            errorMessage = 'Error de conexión. Por favor, verifica tu conexión a internet.';
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'Aceptar'
                        });
                    })
                    .finally(() => {
                        // Restaurar el botón
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        }
                    });
                });
            }
            
            // Eliminar cliente con AJAX
            document.addEventListener('click', function(e) {
                const btnEliminar = e.target.closest('.btn-eliminar');
                if (!btnEliminar) return;

                const boton = btnEliminar;
                const cedula = boton.getAttribute('data-cedula');
                const nombre = boton.getAttribute('data-nombre');
                
                Swal.fire({
                    title: '¿Estás seguro?',
                    html: `¿Deseas eliminar al cliente <strong>${nombre}</strong> con cédula <strong>${cedula}</strong>?<br><br><span class="text-danger">Esta acción no se puede deshacer.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return fetch(`clients-admin.php?action=delete&cedula=${cedula}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error en la red');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message || 'Error al eliminar el cliente');
                            }
                            return data;
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        // Animación de eliminación
                        const fila = document.getElementById(`cliente-${cedula}`);
                        if (fila) {
                            fila.style.transition = 'all 0.3s ease-out';
                            fila.style.opacity = '0';
                            setTimeout(() => {
                                fila.remove();
                                
                                // Verificar si la tabla quedó vacía
                                const tbody = document.getElementById('clientesTableBody');
                                if (tbody.children.length === 0) {
                                    tbody.innerHTML = `
                                        <tr id="no-clientes">
                                            <td colspan="6" class="text-center">
                                                <div class="alert alert-info mb-0">No hay clientes disponibles</div>
                                            </td>
                                        </tr>`;
                                }
                                
                                // Mostrar mensaje de éxito
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Eliminado!',
                                    text: result.value.message || 'El cliente ha sido eliminado correctamente',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }, 300);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: `<p>${error.message || 'Ocurrió un error al intentar eliminar el cliente'}</p>`,
                        confirmButtonText: 'Aceptar'
                    });
                });
            });
            
            // Función para agregar una nueva fila a la tabla
            function agregarFilaCliente(cliente) {
                const tbody = document.getElementById('clientesTableBody');
                const filaVacia = document.getElementById('no-clientes');
                
                // Si existe la fila de "no hay clientes", la eliminamos
                if (filaVacia) {
                    filaVacia.remove();
                }
                
                // Creamos la nueva fila
                const nuevaFila = document.createElement('tr');
                nuevaFila.id = `cliente-${cliente.cedula}`;
                nuevaFila.innerHTML = `
                    <td class="text-center">${cliente.cedula}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.direccion}</td>
                    <td class="text-end">${formatearTelefono(cliente.telefono)}</td>
                    <td class="text-center">${cliente.membresia}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger btn-eliminar" 
                                data-cedula="${cliente.cedula}"
                                data-nombre="${cliente.nombre}">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </td>
                `;
                
                // Agregamos la nueva fila al inicio de la tabla
                tbody.insertBefore(nuevaFila, tbody.firstChild);
            }
            
            // Función para formatear el número de teléfono
            function formatearTelefono(telefono) {
                if (!telefono) return '';
                const telefonoStr = String(telefono);
                if (telefonoStr.length === 11) {
                    return telefonoStr.replace(/(\d{4})(\d{7})/, '$1-$2');
                }
                return telefonoStr;
            }
        });
    </script>
</body>
</html>