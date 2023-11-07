<?php
require_once("../model/banco.php");

class listarController {

    private $lista;

    public function __construct() {
        $this->lista = new Banco();
        if (isset($_GET['setor']) && $_GET['setor'] != '') {
            $this->criarTabelaComFiltro($_GET['setor']);
        } else {
            $this->criarTabela();
        }
    }

    // Criar a tabela de usuários sem filtro
    private function criarTabela() {
        $row = $this->lista->getCadastro();
        foreach ($row as $value) {
            $this->imprimirLinha($value);
        }
    }

    // Criar a tabela de usuários com filtro por setor
    private function criarTabelaComFiltro($setorId) {
        $row = $this->lista->getUsuariosPorSetor($setorId);
        foreach ($row as $value) {
            $this->imprimirLinha($value);
        }
    }

    // Imprimir uma linha da tabela
    private function imprimirLinha($value) {
        echo "<tr>";
        echo "<th>".$value['nome']."</th>"; // 'name' from the users table is aliased as 'nome'
        echo "<td>".$value['email']."</td>";
        echo "<td>".$value['setor']."</td>"; // 'name' from the setores table is aliased as 'setor'
        echo "<td><a class='btn btn-warning' href='editar.php?id=".$value['id']."'>Editar</a>
                  <a class='btn btn-danger' href='../controller/ControllerDeletar.php?id=".$value['id']."'>Excluir</a></td>";
        echo "</tr>";
    }

    // Gerar as opções de setor no formulário de filtro
    public function gerarOpcoesSetor() {
        $setores = $this->lista->getTodosSetores();
        foreach ($setores as $setor) {
            echo "<option value='".$setor['id']."'>".$setor['name']."</option>";
        }
    }
}
?>
