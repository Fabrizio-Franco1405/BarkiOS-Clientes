<?php
require_once __DIR__.'/../core/Database.php';

class Clients {
    private $db;

    public function __construct() {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clientes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clientExists($cedula) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM clientes WHERE cedula = :cedula");
        $stmt->execute([':cedula' => $cedula]);
        return $stmt->fetchColumn() > 0;
    }

    public function add($cedula, $nombre, $direccion, $telefono, $membresia) {
        // Primero verificamos si el cliente ya existe
        if ($this->clientExists($cedula)) {
            throw new Exception("Ya existe un cliente con esta cédula");
        }

        $stmt = $this->db->prepare("
            INSERT INTO clientes (cedula, nombre, direccion, telefono, membresia)
            VALUES (:cedula, :nombre, :direccion, :telefono, :membresia)
        ");
        
        $result = $stmt->execute([
            ':cedula' => $cedula,
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':membresia' => $membresia
        ]);

        if (!$result) {
            throw new Exception("Error al agregar el cliente");
        }

        return true;
    }

    public function truncate() {
        $stmt = $this->db->prepare("TRUNCATE TABLE clientes");
        return $stmt->execute();
    }

    public function delete($cedula) {
        $stmt = $this->db->prepare("DELETE FROM clientes WHERE cedula = :cedula");
        return $stmt->execute([':cedula' => $cedula]);
    }
}