<?php

/**
 * Criado e registrado pelo desenvolvedor Leonardo Cruz.
 * author: Leonardo Cruz
 */
class conexao
{
    //Definição privado dos Atributos da Classe
    private $host;
    private $user;
    private $pass;
    private $db_name;
    private $port;
    private $connection;

    //Método Construtor
    public function __construct($host, $user, $pass, $db, $port)
    {
        $this->setHost($host);
        $this->setUser($user);
        $this->setPass($pass);
        $this->setDb_name($db);
        $this->setPort($port);
    }

    //Encapsulamento GET
    public function getHost()
    {
        return $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function getDb_name()
    {
        return $this->db_name;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getConnection()
    {
        return $this->connection;
    }


    //Encapsulamento SET
    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    public function setDb_name($db_name)
    {
        $this->db_name = $db_name;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }


    //Método de Abertura de Comunicação com Banco de dados
    public function abreConexao()
    {
        $conn = new mysqli(
            $this->getHost(),
            $this->getUser(),
            $this->getPass(),
            $this->getDb_name(),
            $this->getPort()
        );
        if ($conn->connect_errno) {
            printf("Falha ao conectar no Database: %s\n",
                $conn->connect_error);
            die;
        }
        $this->setConnection($conn);
    }

    //Fechar a conexão com banco de dados
    public function fechaConexao()
    {
        return $this->getConnection()->close();
    }

    //Executa sql
    public function executeQuery($sql)
    {
        $this->abreConexao(); //Abrir a conexão
        $conn = $this->getConnection(); //Recupera o obj MySQLi
        $resultado = $conn->query($sql); //Executa o SQL
        if ($resultado === false) {
            printf("Problema ao executar SQL: \n %s", $sql);
            die;
        }
        $this->fechaConexao(); //Fecha a conexão

        return $resultado; //Devolve o resultado
    }

    public function executeS($sql)
    {
        $this->abreConexao(); //Abrir a conexão
        $conn = $this->getConnection(); //Recupera o obj MySQLi
        if ($resultado = $conn->query($sql)) {
                $array = array();
                while ($object = $resultado->fetch_object()) {
                    $array[] = $object;
                }
            $retorno = $array;
            $this->fechaConexao(); //Fecha a conexão
        } else {
            $retorno = "Erro ao executar o comando:\n" . $conn->error;
        }
        return $retorno;
    }
    /**
     * Registra banco principal.
     * Author: Leonardo Cruz
     */
    static public function toConnect()
    {
        $conexao = new conexao("localhost", "root", "", "carteira_digital", "3306");
        return $conexao;
    }
}