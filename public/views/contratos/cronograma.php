<?php
require_once '../../../app/controllers/ContratoControllers.php';
require_once '../../../app/controllers/PagosController.php';
require_once '../../../app/models/Pagos.php';
require_once '../../../app/models/Contrato.php'; // Agregado para obtener nombre del cliente

if (!isset($_GET['idcontrato']) || empty($_GET['idcontrato'])) {
    die('ID de contrato no especificado.');
}

$idcontrato = intval($_GET['idcontrato']);

// Obtener datos del contrato y cliente
$contratoModel = new Contrato();
$contrato = $contratoModel->getById($idcontrato);
$nombreCompleto = $contrato ? $contrato['apellidos'] . ', ' . $contrato['nombres'] : 'Desconocido';

$pagosController = new PagoController();
$cronograma = $pagosController->index($idcontrato);

ob_start();
?>

<h1>Pagos del Contrato - <?= htmlspecialchars($nombreCompleto) ?></h1>

<?php if (!empty($cronograma)): ?>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>N° Cuota</th>
                <th>Fecha de Pago</th>
                <th>Monto</th>
                <th>Penalidad</th>
                <th>Medio</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cronograma as $pago): ?>
                <tr>
                    <td><?= htmlspecialchars($pago['numcuota']) ?></td>
                    <td>
                        <?= $pago['fechapago'] ? date('d/m/Y', strtotime($pago['fechapago'])) : '<em>Pendiente</em>' ?>
                    </td>
                    <td><?= number_format($pago['monto'], 2) ?></td>
                    <td><?= number_format($pago['penalidad'], 2) ?></td>
                    <td><?= $pago['medio'] ?? '<em>No pagado</em>' ?></td>
                    <td>
                        <?php
                        if (!empty($pago['medio'])) {
                            echo '<span class="badge bg-success">Pagado</span>';
                        } else {
                            echo '<span class="badge bg-warning text-dark">Pendiente</span>';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No se encontraron pagos para este contrato.</p>
<?php endif; ?>

<a href="indexContrato.php" class="btn btn-secondary mt-3">Volver a contratos</a>

<?php
$content = ob_get_clean();
$title = "Cronograma de pagos";
$active = "contratos";
require '../../../dashboard.php';
?>
