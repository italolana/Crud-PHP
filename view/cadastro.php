<!DOCTYPE HTML>
<html>
<?php include("head.php") ?>

<body>
<?php include("menu.php") ?>

<div class="row">
    <form method="post" action="../controller/ControllerCadastro.php" id="form" name="form" onsubmit="return validar();" class="col-10">
        <div class="form-group">
            <input class="form-control" type="text" id="nome" name="nome" placeholder="Nome" required autofocus>
            <input class="form-control" type="text" id="email" name="email" placeholder="Email" required>

            <!-- Campo select para escolher um setor existente -->
            <select class="form-control" id="setorExistente" name="setorExistente" >
                <option value="">Selecione um setor</option>
                <?php
                require_once("../model/banco.php");
                $banco = new Banco();
                $setores = $banco->getTodosSetores();
                foreach ($setores as $setor):
                    echo "<option value='" . $setor['id'] . "'>" . $setor['name'] . "</option>";
                endforeach;
                ?>
            </select>


            <input class="form-control" type="text" id="novoSetor" name="novoSetor" placeholder="Ou adicione um novo setor">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success" id="cadastrar">Cadastrar</button>
        </div>
    </form>
</div>

<script>
    function validar() {

        var setorExistente = document.getElementById('setorExistente').value;
        var novoSetor = document.getElementById('novoSetor').value;

        if (setorExistente === "" && novoSetor === "") {
            alert('Por favor, selecione um setor ou adicione um novo.');
            return false;
        }
        return true;
    }
</script>

</body>
</html>
