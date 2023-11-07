<?php
require_once("../model/banco.php");

class editarController {

    private $editar;
    private $nome;
    private $email;
    private $setor;
    private $id;

    public function __construct($id){
        $this->editar = new Banco();
        $this->id = $id;
        $this->criarFormulario($id);
    }

    private function criarFormulario($id){
        $row = $this->editar->pesquisaUsuario($id);
        $this->nome  = isset($row['name']) ? $row['name'] : null;
        $this->email = isset($row['email']) ? $row['email'] : null;

        if($this->nome && $this->email){
            $this->setor = $this->editar->pesquisaSetoresUsuario($id);
        }
    }

    public function getTodosSetores(){
        return $this->editar->getTodosSetores();
    }

    public function editarFormulario($nome, $email, $setor, $id){
        if($this->editar->updateUsuarioSetores($nome, $email, $setor, $id)){
            echo "<script>alert('Registro incluído com sucesso!');document.location='../view/index.php'</script>";
        } else {
            echo "<script>alert('Erro ao gravar registro!');history.back()</script>";
        }
    }


    public function getNome(){
        return $this->nome;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getSetores(){
        return $this->setor;
    }

    public function getIdUsuario(){
        return $this->id;
    }
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<script>alert('ID inválido.');history.back()</script>";
    exit;
}

$editar = new editarController($id);


if(isset($_POST['submit'])){
    $nome = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $setor = $_POST['setor'];
    $idUser = $editar->getIdUsuario();

    $editar->editarFormulario($nome, $email, $setor, $idUser);
}
?>
