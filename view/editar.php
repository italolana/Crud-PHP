<!DOCTYPE HTML>
<html>
<?php include("head.php") ?>

<body>
<?php include("menu.php") ?>

<?php
require_once("../controller/ControllerEditar.php");

$editar = new editarController($id);
$allSetores = $editar->getTodosSetores();
$usuarioSetores = $editar->getSetores();
?>

<div class="row">
    <form method="post" action="../controller/ControllerEditar.php" id="formSetores" name="formSetores" class="col-10">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input class="form-control" type="text" id="nome" name="nome" value="<?php echo $editar->getNome(); ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input class="form-control" type="email" id="email" name="email" value="<?php echo $editar->getEmail(); ?>" required>
        </div>
        <div class="form-group">
            <label>Setores:</label>
            <?php foreach ($allSetores as $setor): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="setor[]" value="<?php echo $setor['id']; ?>" id="setor<?php echo $setor['id']; ?>"
                        <?php echo (in_array($setor['id'], array_column($usuarioSetores, 'id')) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="setor<?php echo $setor['id']; ?>">
                        <?php echo htmlspecialchars($setor['name']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
            <button type="button" onclick="toggleCheckboxes(true)">Selecionar Todos</button>
            <button type="button" onclick="toggleCheckboxes(false)">Desmarcar Todos</button>
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $editar->getIdUsuario(); ?>">
            <button type="submit" class="btn btn-success" id="editar" name="submit" value="editar">Editar</button>
        </div>
    </form>
</div>

<script>
    function toggleCheckboxes(checked) {
        var checkboxes = document.querySelectorAll('.form-check-input');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = checked;
        });
    }
</script>
</body>
</html>
