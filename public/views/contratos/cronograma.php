<?php
require_once '../../../app/controllers/ContratoControllers.php';
require_once '../../../app/controllers/PagosController.php';
require_once '../../../app/models/Pagos.php';

if (!isset($_GET['idcontrato']) || empty($_GET['idcontrato'])) {
    die('ID de contrato no especificado.');
}

$idcontrato = intval($_GET['idcontrato']);

$pagosController = new PagoController(); // Nombre corregido
$cronograma = $pagosController->index($idcontrato); // Variable corregida

ob_start();
?>

<h1>Cronograma de pagos - Contrato #<?= htmlspecialchars($idcontrato) ?></h1>

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
                            // Si tiene medio de pago, está pagado
                            echo '<span class="badge bg-success">Pagado</span>';
                        } else {
                            // Si no tiene medio de pago, está pendiente
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

<a href="index.php" class="btn btn-secondary mt-3">Volver a contratos</a>

<?php
$content = ob_get_clean();
$title = "Cronograma de pagos";
$active = "contratos";
require '../../../dashboard.php';
?>
