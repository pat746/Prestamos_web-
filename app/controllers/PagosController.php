<?php
require_once __DIR__ . '/../models/Pagos.php';
require_once __DIR__ . '/../models/Contrato.php';

class PagoController {
    private $pagoModel;
    private $contratoModel;

    public function __construct() {
        $this->pagoModel = new Pago();
        $this->contratoModel = new Contrato();
    }

    public function index($idcontrato) {
        // Obtener datos del contrato
        $contrato = $this->contratoModel->getById($idcontrato);
        if (!$contrato) {
            return [];
        }

        $fechainicio = $contrato['fechainicio'];
        $diapago = (int)$contrato['diapago'];
        $numcuotas = (int)$contrato['numcuotas'];
        $monto_cuota = $contrato['monto'];

        // Obtener pagos ya realizados
        $pagosStmt = $this->pagoModel->getByContrato($idcontrato);
        $pagosRealizados = $pagosStmt->fetchAll(PDO::FETCH_ASSOC);

        $pagosPorCuota = [];
        foreach ($pagosRealizados as $pago) {
            $pagosPorCuota[$pago['numcuota']] = $pago;
        }

        // Ajustar la fecha de inicio al primer día del mes siguiente
        $fechaInicioObj = new DateTime($fechainicio);
        $fechaInicioObj->modify('first day of next month');

        $cronograma = [];

        for ($i = 0; $i < $numcuotas; $i++) {
            $numcuota = $i + 1;
            $fechaPago = clone $fechaInicioObj;
            $fechaPago->modify("+$i month");

            // Ajustar día de pago al último día del mes si es necesario
            $ultimoDiaMes = (int)$fechaPago->format('t');
            $diaPagoMes = min($diapago, $ultimoDiaMes);
            $fechaPago->setDate($fechaPago->format('Y'), $fechaPago->format('m'), $diaPagoMes);

            if (isset($pagosPorCuota[$numcuota])) {
                $pago = $pagosPorCuota[$numcuota];
                $cronograma[] = [
                    'numcuota' => $numcuota,
                    'fechapago' => $pago['fechapago'] ?: $fechaPago->format('Y-m-d'),
                    'monto' => $pago['monto'] ?: $monto_cuota,
                    'penalidad' => $pago['penalidad'] ?: 0,
                    'medio' => $pago['medio'] ?? null,
                    'estado' => $pago['fechapago'] ? 'Pagado' : 'Pendiente',
                ];
            } else {
                $cronograma[] = [
                    'numcuota' => $numcuota,
                    'fechapago' => $fechaPago->format('Y-m-d'),
                    'monto' => $monto_cuota,
                    'penalidad' => 0,
                    'medio' => null,
                    'estado' => 'Pendiente',
                ];
            }
        }

        return $cronograma;
    }
}
?>
