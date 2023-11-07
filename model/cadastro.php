<?php
require_once("banco.php");

class Cadastro extends Banco {

    private $nome;
    private $email;
    private $setor;

    //Metodos Set
    public function setNome($string){
        $this->nome = $string;
    }
    public function setEmail($string){
        $this->email = $string;
    }
    public function setSetor($string){
        $this->setor = $string;
    }

    //Metodos Get
    public function getNome(){
        return $this->nome;
    }
    public function getEmail(){
        return $this->email;
    }


    public function incluir(){
        return $this->setCadastro($this->getNome(),$this->getEmail());
    }
}
?>
