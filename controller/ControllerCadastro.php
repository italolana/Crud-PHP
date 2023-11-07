<?php
require_once("../model/cadastro.php");
class cadastroController{

    private $cadastro;

    public function __construct(){
        $this->cadastro = new Cadastro();
        $this->incluir();
    }

    private function incluir(){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $setorExistente = isset($_POST['setorExistente']) ? $_POST['setorExistente'] : null;
        $novoSetor = isset($_POST['novoSetor']) ? $_POST['novoSetor'] : '';

        // Verifica se um novo setor foi adicionado, se não, usa o setor existente
        $setorId = null;
        if (!empty($novoSetor)) {
            $setorId = $this->cadastro->addSetor($novoSetor);
            if ($setorId === false) {
                echo "<script>alert('Erro ao adicionar o novo setor!');history.back()</script>";
                exit;
            }
        } elseif (!empty($setorExistente)) {
            $setorId = $setorExistente;
        }

        if(empty($setorId)) {
            echo "<script>alert('Selecione um setor ou adicione um novo.');history.back()</script>";
            exit;
        }

        // Continua com o cadastro usando o ID do setor
        $result = $this->cadastro->setUsuario($nome, $email, $setorId);
        if($result){
            echo "<script>alert('Registro incluído com sucesso!');document.location='../view/cadastro.php'</script>";
        } else {
            echo "<script>alert('Erro ao gravar registro!');history.back()</script>";
        }
    }
}

new cadastroController();
