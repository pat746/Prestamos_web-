<?php
require_once '../../../app/controllers/ContratoControllers.php';
$controller = new ContratoController();
$contratos = $controller->index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $controller->store($_POST);
    if ($success) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error al registrar contrato. Datos incorrectos o faltantes.";
    }
}
ob_start();
?>

<h1>Contratos</h1>

<!-- Botón abrir modal -->
<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalRegistro">
    Registrar Contrato
</button>

<!-- Tabla -->
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Beneficiario</th>
            <th>Monto</th>
            <th>Interés (%)</th>
            <th>Fecha Inicio</th>
            <th>Día Pago</th>
            <th>Número Cuotas</th>
            <th>Estado</th>
            <th>Acción</th> <!-- Nueva columna -->
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($contratos)): ?>
            <?php foreach ($contratos as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['idcontrato']) ?></td>
                    <td><?= htmlspecialchars($c['apellidos'] . ', ' . $c['nombres']) ?></td>
                    <td><?= number_format($c['monto'], 2) ?></td>
                    <td><?= htmlspecialchars($c['interes']) ?></td>
                    <td><?= htmlspecialchars($c['fechainicio']) ?></td>
                    <td><?= htmlspecialchars($c['diapago']) ?></td>
                    <td><?= htmlspecialchars($c['numcuotas']) ?></td>
                    <td><?= htmlspecialchars($c['estado']) ?></td>
                    <td>
                        <a href="cronograma.php?idcontrato=<?= urlencode($c['idcontrato']) ?>" class="btn btn-primary btn-sm">
                            Ver
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="9" class="text-center">No hay contratos registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal Registro (sin cambios) -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formRegistro">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRegistroLabel">Registrar Contrato</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="mb-3">
              <label class="form-label">Beneficiario:</label>
              <select name="idbeneficiario" class="form-select" required>
                  <option value="" disabled selected>Seleccione un beneficiario</option>
                  <?php
                  require_once '../../../app/controllers/BeneficiarioController.php';
                  $benController = new BeneficiarioController();
                  $beneficiarios = $benController->beneficiariosSinContrato();
                  foreach ($beneficiarios as $b):
                  ?>
                      <option value="<?= htmlspecialchars($b['idbeneficiario']) ?>">
                        <?= htmlspecialchars($b['apellidos'] . ', ' . $b['nombres']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="mb-3">
              <label class="form-label">Monto:</label>
              <input type="number" step="0.01" min="0" class="form-control" name="monto" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Interés (%):</label>
              <input type="number" step="0.01" min="0" class="form-control" name="interes" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Fecha de inicio:</label>
              <input type="date" class="form-control" name="fechainicio" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Día de pago:</label>
              <input type="number" min="1" max="31" class="form-control" name="diapago" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Número de cuotas:</label>
              <input type="number" min="1" class="form-control" name="numcuotas" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Estado:</label>
              <select name="estado" class="form-select" required>
                  <option value="ACT" selected>Activo</option>
                  <option value="FIN">Finalizado</option>
              </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="validarYConfirmar()">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function validarYConfirmar() {
    const form = document.getElementById("formRegistro");
    const campos = form.querySelectorAll("input, select");
    let valid = true;

    campos.forEach(campo => {
        if (!campo.checkValidity()) {
            campo.classList.add("is-invalid");
            valid = false;
        } else {
            campo.classList.remove("is-invalid");
        }
    });

    if (!valid) {
        alert("Por favor completa correctamente todos los campos.");
        return;
    }

    if (confirm("¿Está seguro que desea registrar el contrato?")) {
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
$title = "Lista de Contratos";
$active = "contratos";
require '../../../dashboard.php';
?>
