<?php

require_once("../init.php");
class Banco {

    protected $mysqli;

    public function __construct() {
        $this->conexao();
    }

    private function conexao() {
        $this->mysqli = new mysqli(BD_SERVIDOR, BD_USUARIO, BD_SENHA, BD_BANCO);
    }

    public function setCadastro($nome, $email) {
        $stmt = $this->mysqli->prepare("INSERT INTO users (`name`, `email`) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        if ($stmt->execute() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function getCadastro() {
        $result = $this->mysqli->query("
        SELECT users.id, users.name as nome, users.email, setores.name as setor
        FROM users
        LEFT JOIN user_setores ON users.id = user_setores.user_id
        LEFT JOIN setores ON user_setores.setor_id = setores.id
    ");
        $array = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $array[] = $row;
        }
        return $array;
    }

    public function deleteCadastro($id) {
        // Desativa a verificação de chaves estrangeiras para permitir a exclusão temporária
        $this->mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");

        // Exclui os registros relacionados da tabela 'user_setores' para o usuário específico
        $this->mysqli->query("DELETE FROM `user_setores` WHERE `user_id` = '" . $id . "';");

        // Em seguida, exclui o usuário da tabela 'users'
        if ($this->mysqli->query("DELETE FROM `users` WHERE `id` = '" . $id . "';") === TRUE) {
            // Reativa a verificação de chaves estrangeiras
            $this->mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");
            return true;
        } else {
            // Se a exclusão falhar, reativa as chaves estrangeiras e retorna falso
            $this->mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");
            return false;
        }
    }

    public function pesquisaUsuario($id) {
        $stmt = $this->mysqli->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_array(MYSQLI_ASSOC);
    }

    public function pesquisaSetoresUsuario($id) {
        $stmt = $this->mysqli->prepare("
            SELECT s.id, s.name 
            FROM setores s 
            INNER JOIN user_setores us ON s.id = us.setor_id 
            WHERE us.user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $setores = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $setores[] = $row;
        }
        return $setores;
    }

    public function updateUsuarioSetores($nome, $email, $setores, $id) {
        $this->mysqli->begin_transaction();

        try {
            $stmt = $this->mysqli->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $nome, $email, $id);
            $stmt->execute();

            $stmt = $this->mysqli->prepare("DELETE FROM user_setores WHERE user_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $stmt = $this->mysqli->prepare("INSERT INTO user_setores (user_id, setor_id) VALUES (?, ?)");
            foreach ($setores as $setor_id) {
                // Garanta que $setor_id seja um inteiro e exista na tabela setores.
                $stmt->bind_param("ii", $id, $setor_id);
                $stmt->execute();
            }

            $this->mysqli->commit();
            return true;
        } catch (mysqli_sql_exception $exception) {
            $this->mysqli->rollback();
            throw $exception;
        }
    }

    public function getTodosSetores() {
        $result = $this->mysqli->query("SELECT id, name FROM setores");
        $setores = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $setores[] = $row;
        }
        return $setores;
    }
    // Função para buscar todos os setores
    public function addSetor($nomeSetor) {
        if(empty($nomeSetor)) {
            return false; // Retorna false se o nome do setor estiver vazio
        }
        $stmt = $this->mysqli->prepare("INSERT INTO setores (`name`) VALUES (?)");
        $stmt->bind_param("s", $nomeSetor);
        if ($stmt->execute()) {
            return $this->mysqli->insert_id; // Retorna o ID do novo setor
        } else {
            return false;
        }
    }
    public function setUsuario($nome, $email, $setorId) {
        $stmt = $this->mysqli->prepare("INSERT INTO users (`name`, `email`) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $email);
        if ($stmt->execute()) {
            $userId = $this->mysqli->insert_id;
            $stmt = $this->mysqli->prepare("INSERT INTO user_setores (user_id, setor_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $userId, $setorId);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }
    public function getUsuariosPorSetor($setorId) {
        $stmt = $this->mysqli->prepare("
        SELECT users.id, users.name as nome, users.email, setores.name as setor
        FROM users
        JOIN user_setores ON users.id = user_setores.user_id
        JOIN setores ON user_setores.setor_id = setores.id
        WHERE setores.id = ?
    ");
        $stmt->bind_param("i", $setorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuarios = [];
        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $usuarios[] = $row;
        }
        return $usuarios;
    }

}
?>
