<?php
require_once __DIR__ . '/../config/conexion.php';

class Contrato {
    private $conn;
    private $table = 'contratos';

    public $idcontrato;
    public $idbeneficiario;
    public $monto;
    public $interes;
    public $fechainicio;
    public $diapago;
    public $numcuotas;
    public $estado;
    public $creado;
    public $modificado;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los contratos, puedes unir con beneficiario para mostrar nombre
    public function getAll(){
        $query = "SELECT c.*, b.apellidos, b.nombres 
                  FROM " . $this->table . " c
                  JOIN beneficiarios b ON c.idbeneficiario = b.idbeneficiario
                  ORDER BY c.creado DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Crear nuevo contrato
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (idbeneficiario, monto, interes, fechainicio, diapago, numcuotas, estado)
                  VALUES (:idbeneficiario, :monto, :interes, :fechainicio, :diapago, :numcuotas, :estado)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':idbeneficiario', $this->idbeneficiario);
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':interes', $this->interes);
        $stmt->bindParam(':fechainicio', $this->fechainicio);
        $stmt->bindParam(':diapago', $this->diapago);
        $stmt->bindParam(':numcuotas', $this->numcuotas);
        $stmt->bindParam(':estado', $this->estado);

        return $stmt->execute();
    }
    public function getById($idcontrato) {
    $query = "SELECT * FROM " . $this->table . " WHERE idcontrato = :idcontrato";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':idcontrato', $idcontrato, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    // Puedes agregar más métodos según necesites, ej. getById, update, delete...
}
?>
