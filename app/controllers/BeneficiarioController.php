<?php
require_once __DIR__ . '/../models/Beneficiario.php';
require_once __DIR__ . '/../config/conexion.php'; // Asegúrate de que esta ruta sea correcta

class BeneficiarioController {
    private $beneficiario;
    private $db;

    public function __construct(){
        $this->beneficiario = new Beneficiario();
        $this->db = new Database(); // Instancia de la clase Database
    }

    // Listar todos los beneficiarios
    public function index() {
        $stmt = $this->beneficiario->getAll();
        $beneficiarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $beneficiarios;
    }

    // Listar beneficiarios sin contrato
    public function beneficiariosSinContrato() {
        $conn = $this->db->getConnection(); // Obtener conexión

        $sql = "
            SELECT b.idbeneficiario, b.nombres, b.apellidos
            FROM beneficiarios b
            LEFT JOIN contratos c ON b.idbeneficiario = c.idbeneficiario
            WHERE c.idbeneficiario IS NULL
        ";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mostrar formulario para crear beneficiario
    public function create() {
        // Se puede usar para mostrar formulario si se desea
    }

    // Guardar beneficiario nuevo
    public function store($data) {
        $this->beneficiario->apellidos = $data['apellidos'];
        $this->beneficiario->nombres = $data['nombres'];
        $this->beneficiario->dni = $data['dni'];
        $this->beneficiario->telefono = $data['telefono'];
        $this->beneficiario->direccion = $data['direccion'] ?? null;

        return $this->beneficiario->create();
    }
}
?>
