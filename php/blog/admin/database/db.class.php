<?php

class db {

    private $host     = 'localhost';
    private $user     = 'root';
    private $password = '';
    private $port     = '3306';
    private $dbname   = 'db_pweb1_2026_1';
    private $table_name;
    private $conn; // conexão fica guardada para reutilizar

    public function __construct($table_name)
    {
        $this->table_name = $table_name;
        $this->conn = $this->connect(); // cria a conexão uma única vez
    }

    // Método privado: apenas a própria classe pode chamar
    private function connect()
    {
        try {
            return new PDO(
                "mysql:host=$this->host;dbname=$this->dbname;port=$this->port;charset=utf8",
                $this->user,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        } catch (PDOException $e) {
            die('Erro na conexão: ' . $e->getMessage());
        }
    }
    //SELECT * FROM tabela
    public function all(){
        $sql = "SELECT*FROM $this->table_name";
        $st = $this->conn->prepare($sql);
        $st->execute();

        return $st->fetchAll(PDO::FETCH_CLASS);
    }

    public function find($id){
        $sql = "SELECT*FROM $this->table_name WHERE id = ?";
        $st = $this->conn->prepare($sql);
        $st->execute();

        return $st->fetchObject();
    }
    public function findBy($campo, $valor){
        $sql = "SELECT*FROM $this->table_name WHERE $campo = ?";
        $st = $this->conn->prepare($sql);
        $st->execute();//TA CERTA ESSA LINHA?

        return $st->fetchObject;
    }

    //INSERT INTO `db_pweb1_2026_!`.`aluno` (`nome`, `telefone`, `email`) VALUES ('Arthur Brenan', '(49)999889988', 'arthur@gmail.com');
    public function store($dados){
        $campos = "";
        $marcadores = "";
        $vetorData = [];
        $sep = "";

        foreach($dados as $campo => $valor){
            $campos .= $sep . $campo;
            $marcadores.= $sep . "?";
            $vetorData[]=$valor;
            $sep = ",";
        }
        $sql = "INSERT INTO $this->table_name ($campos) VALUES ($marcadores);";
        try{
            $st = $this->conn->prepare($sql);
            $st->execute($vetorData);
        }catch(PDOException $e){
            throw new Exception("Erro ao inserir: ", $e->getMessage());
        }

    }
    //UPDATE tabela SET 'campo1' = ?;
    public function uptade($dados){
        $campos = "";
        $marcadores = "";
        $vetorData = [];
        $sep = "";

        foreach($dados as $campo => $valor){
            if ($campo !== 'id'){

                $campos .= $sep . " $campo= ?";
                $vetorData[]=$valor;
                $sep = ", ";
            }
        }
        $sql = "UPDATE $this->table_name SET $campos  WHERE id=?;";

        try{
            $st = $this->conn->prepare($sql);
            $st->execute($vetorData);
        }catch(PDOException $e){
            throw new Exception("Erro ao inserir: ", $e->getMessage());
        }

    }
}