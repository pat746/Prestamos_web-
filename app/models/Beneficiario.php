<?php
require_once __DIR__ . '/../config/conexion.php';

class Beneficiario {
    private $conn;
    private $table = 'beneficiarios';

    public $idbeneficiario;
    public $apellidos;
    public $nombres;
    public $dni;
    public $telefono;
    public $direccion;
    public $creado;
    public $modificado;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll(){
        $query = "SELECT idbeneficiario, apellidos, nombres, dni, telefono, direccion FROM " . $this->table . " ORDER BY apellidos ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function create() {
    $query = "INSERT INTO " . $this->table . " (apellidos, nombres, dni, telefono, direccion)
              VALUES (:apellidos, :nombres, :dni, :telefono, :direccion)";
    
    $stmt = $this->conn->prepare($query);
    
    $stmt->bindParam(':apellidos', $this->apellidos);
    $stmt->bindParam(':nombres', $this->nombres);
    $stmt->bindParam(':dni', $this->dni);
    $stmt->bindParam(':telefono', $this->telefono);
    $stmt->bindParam(':direccion', $this->direccion);
    
    return $stmt->execute();
}

}
?>
