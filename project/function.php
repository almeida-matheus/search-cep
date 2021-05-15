<?php

class Address
{
    private $mysql;
    public function __construct(mysqli $mysql)
    {
        $this->mysql = $mysql;
    }

    public function exibirTodos(): array
    {

        $resultado = $this->mysql->query('SELECT id, nome, cep, estado, cidade, rua, numero FROM address');
        $addresses = $resultado->fetch_all(MYSQLI_ASSOC);

        return $addresses;
    }

    public function exibirPesquisa(string $cep): array
    {
        $search = $this->mysql->prepare('SELECT id, nome, cep, estado, cidade, rua, numero FROM address WHERE cep LIKE ?');
        $search->bind_param('s', $cep);

        $search->execute();

        if(!$search->execute()){
            header('Location: index.php');
            die();
        }

        $result = $search->get_result();
        $users  = $result->fetch_all(MYSQLI_ASSOC);

        // echo "<pre>";
        // var_dump($users);
        // echo "</pre>";

        return $users;
    }

    public function adicionar(string $nome, int $cep, string $rua, int $numero, string $cidade, string $estado): void
    {
        $insertAddress = $this->mysql->prepare('INSERT INTO address (nome, cep, estado, cidade, rua, numero) VALUES(?,?,?,?,?,?);');
        $insertAddress->bind_param('sisssi', $nome, $cep, $estado, $cidade, $rua, $numero);
        $insertAddress->execute();
    }

    public function remover(int $id): void
    {
        $removeAddress = $this->mysql->prepare('DELETE FROM address WHERE id = ?');
        $removeAddress->bind_param('i', $id);
        $removeAddress->execute();
    }

    // redireciona('/projects/search-cep/project/index.php');
    function redireciona(string $url): void
    {
        header("Location: $url");
        die();
    }
}
