<?php
require_once __DIR__ . '/../config/conexion.php';

class Pago {
    private $conn;
    private $table = 'pagos';

    public $idpago;
    public $idcontrato;
    public $numcuota;
    public $fechapago;
    public $monto;
    public $penalidad;
    public $medio;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Obtener todos los pagos de un contrato
    public function getByContrato($idcontrato) {
        $query = "SELECT * FROM " . $this->table . " WHERE idcontrato = :idcontrato ORDER BY numcuota ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idcontrato', $idcontrato, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Crear nuevo pago
    public function create() {
        $query = "INSERT INTO " . $this->table . " (idcontrato, numcuota, fechapago, monto, penalidad, medio)
                  VALUES (:idcontrato, :numcuota, :fechapago, :monto, :penalidad, :medio)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':idcontrato', $this->idcontrato);
        $stmt->bindParam(':numcuota', $this->numcuota);
        $stmt->bindParam(':fechapago', $this->fechapago);
        $stmt->bindParam(':monto', $this->monto);
        $stmt->bindParam(':penalidad', $this->penalidad);
        $stmt->bindParam(':medio', $this->medio);

        return $stmt->execute();
    }

    // Puedes agregar métodos para actualizar, eliminar o buscar por idpago si necesitas
}
?>
