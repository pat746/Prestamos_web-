<?php
require_once '../../../app/controllers/BeneficiarioController.php';
$controller = new BeneficiarioController();
$beneficiarios = $controller->index();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $controller->store($_POST);
    if ($success) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error al registrar beneficiario. DNI duplicado o datos incorrectos.";
    }
}
ob_start();
?>

<h1>Beneficiarios</h1>

<!-- Botón abrir modal -->
<button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalRegistro">
    Registrar Beneficiario
</button>

<!-- Tabla -->
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Apellidos</th>
            <th>Nombres</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Dirección</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($beneficiarios)): ?>
            <?php foreach ($beneficiarios as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['idbeneficiario']) ?></td>
                    <td><?= htmlspecialchars($b['apellidos']) ?></td>
                    <td><?= htmlspecialchars($b['nombres']) ?></td>
                    <td><?= htmlspecialchars($b['dni']) ?></td>
                    <td><?= htmlspecialchars($b['telefono']) ?></td>
                    <td><?= htmlspecialchars($b['direccion'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No hay beneficiarios registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" id="formRegistro">
        <div class="modal-header">
          <h5 class="modal-title" id="modalRegistroLabel">Registrar Beneficiario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <?php if (!empty($error)): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>
          <div class="mb-3">
              <label class="form-label">Apellidos:</label>
              <input type="text" class="form-control" name="apellidos" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Nombres:</label>
              <input type="text" class="form-control" name="nombres" required>
          </div>
          <div class="mb-3">
              <label class="form-label">DNI:</label>
              <input type="text" class="form-control" name="dni" required pattern="\d{8}" title="8 dígitos">
          </div>
          <div class="mb-3">
              <label class="form-label">Teléfono:</label>
              <input type="text" class="form-control" name="telefono" required pattern="\d{9}" title="9 dígitos">
          </div>
          <div class="mb-3">
              <label class="form-label">Dirección:</label>
              <textarea class="form-control" name="direccion" required></textarea>
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

<!-- Validación + confirmación -->
<script>
function validarYConfirmar() {
    const form = document.getElementById("formRegistro");
    const campos = form.querySelectorAll("input, textarea");
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

    if (confirm("¿Está seguro que desea registrar al beneficiario?")) {
        form.submit();
    }
}
</script>

<?php
$content = ob_get_clean();
$title = "Lista de Beneficiarios";
$active = "beneficiarios";
require '../../../dashboard.php';
?>
