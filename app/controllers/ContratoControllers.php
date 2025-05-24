<?php
require_once __DIR__ . '/../models/Contrato.php';

class ContratoController {
    private $contrato;

    public function __construct(){
        $this->contrato = new Contrato();
    }

    // Listar todos los contratos
    public function index() {
        $stmt = $this->contrato->getAll();
        $contratos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $contratos;
    }

    // Mostrar formulario para crear contrato
    public function create(){
        // Aquí idealmente deberías pasar la lista de beneficiarios para el select
        require_once __DIR__ . '/../models/Beneficiario.php';
        $beneficiariosModel = new Beneficiario();
        $stmt = $beneficiariosModel->getAll();
        $beneficiarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'views/contratos/create.php';
    }

    // Guardar nuevo contrato
    public function store($data) {
        $this->contrato->idbeneficiario = $data['idbeneficiario'];
        $this->contrato->monto = $data['monto'];
        $this->contrato->interes = $data['interes'];
        $this->contrato->fechainicio = $data['fechainicio'];
        $this->contrato->diapago = $data['diapago'];
        $this->contrato->numcuotas = $data['numcuotas'];
        $this->contrato->estado = $data['estado'] ?? 'ACT'; // Por defecto activo

        return $this->contrato->create();
    }
    
}

?>
