<?php
require_once __DIR__.'/../../models/Clients.php';

class ClientsController {
    private $clientsModel;

    public function __construct() {
        $this->clientsModel = new Clients();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? '';
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
                $this->handleAddclients();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'delete') {
                $this->handleDeleteclients();
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'truncate') {
                $this->handleTruncate();
            } else if ($isAjax) {
                // Si es AJAX pero no coincide con ninguna acción, devolver error
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Acción no válida']);
                exit();
            }
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                exit();
            } else {
                die("Error: " . $e->getMessage());
            }
        }
    }

    public function getclientss() {
        return $this->clientsModel->getAll();
    }

    private function handleAddclients() {
        // Verificar si es una petición AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        $response = ['success' => false, 'message' => ''];
        
        try {
            // Limpiar cualquier salida anterior
            if (ob_get_length()) ob_clean();
            $required = ['cedula', 'nombre', 'direccion', 'telefono', 'membresia'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }

            $cedula = htmlspecialchars(trim($_POST['cedula']));
            $nombre = htmlspecialchars(trim($_POST['nombre']));
            $direccion = htmlspecialchars(trim($_POST['direccion']));
            $telefono = htmlspecialchars(trim($_POST['telefono']));
            $membresia = htmlspecialchars(trim($_POST['membresia']));

            // Verifica si ya existe antes de insertar
            if ($this->clientsModel->clientExists($cedula)) {
                throw new Exception("Ya existe un cliente con esta cédula");
            }

            // Insertar el cliente
            $success = $this->clientsModel->add($cedula, $nombre, $direccion, $telefono, $membresia);

            if ($success) {
                $response['success'] = true;
                $response['message'] = 'Cliente agregado correctamente';
                $response['cliente'] = [
                    'cedula' => $cedula,
                    'nombre' => $nombre,
                    'direccion' => $direccion,
                    'telefono' => $telefono,
                    'membresia' => $membresia
                ];
            } else {
                throw new Exception("Error al agregar el cliente");
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            if ($response['success']) {
                header("Location: clients-admin.php?success=add");
            } else {
                header("Location: clients-admin.php?error=" . urlencode($response['message']) . "&cedula=" . urlencode($_POST['cedula'] ?? ''));
            }
            exit();
        }
    }

    private function handleDeleteclients() {
        // Verificar si es una petición AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        
        $response = ['success' => false, 'message' => ''];
        
        try {
            // Limpiar cualquier salida anterior
            if (ob_get_length()) ob_clean();
            if (!isset($_GET['cedula']) || empty($_GET['cedula'])) {
                throw new Exception("Cédula de cliente inválida");
            }

            $cedula = $_GET['cedula'];
            $success = $this->clientsModel->delete($cedula);

            if ($success) {
                $response['success'] = true;
                $response['message'] = 'Cliente eliminado correctamente';
                $response['cedula'] = $cedula;
            } else {
                throw new Exception("Error al eliminar el cliente");
            }
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
        
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        } else {
            if ($response['success']) {
                header("Location: clients-admin.php?success=delete");
            } else {
                header("Location: clients-admin.php?error=" . urlencode($response['message']));
            }
            exit();
        }
    }
    
    private function handleTruncate() {
        $this->clientsModel->truncate();
        header("Location: clients-admin.php?success=add");
        exit();
    }
}

// Instanciar y ejecutar
$controller = new ClientsController();
$controller->handleRequest();
$clientss = $controller->getclientss();