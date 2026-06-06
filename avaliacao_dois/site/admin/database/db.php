<?php

class db {

    private $host     = 'localhost';
    private $user     = 'root';
    private $password = '';
    private $port     = '3306';
    private $dbname   = 'av2';
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

    // SELECT * FROM tabela
    public function all(){
        $sql = "SELECT * FROM $this->table_name";
        $st = $this->conn->prepare($sql);
        $st->execute();

        return $st->fetchAll(PDO::FETCH_CLASS);
    }

    // Busca por ID
    public function find($id){
        $sql = "SELECT * FROM $this->table_name WHERE id = ?";
        $st = $this->conn->prepare($sql);
        $st->execute([$id]); // Corrigido: Passando o ID para o motor do banco

        return $st->fetchObject();
    }

    // Busca por qualquer outro campo (ex: email)
    public function findBy($campo, $valor){
        $sql = "SELECT * FROM $this->table_name WHERE $campo = ?";
        $st = $this->conn->prepare($sql);
        $st->execute([$valor]); // Corrigido: Agora está certa! Passando o valor.

        return $st->fetchObject(); // Corrigido: Adicionado os parênteses ()
    }

    // INSERT INTO usuario (...) VALUES (...);
    public function store($dados){
        $campos = "";
        $marcadores = "";
        $vetorData = [];
        $sep = "";

        foreach($dados as $campo => $valor){
            $campos .= $sep . $campo;
            $marcadores .= $sep . "?";
            $vetorData[] = $valor;
            $sep = ",";
        }
        $sql = "INSERT INTO $this->table_name ($campos) VALUES ($marcadores);";
        try{
            $st = $this->conn->prepare($sql);
            $st->execute($vetorData);
        }catch(PDOException $e){
            // Corrigido: O construtor de Exception aceita apenas a string como primeiro parâmetro
            throw new Exception("Erro ao inserir: " . $e->getMessage());
        }
    }

    // UPDATE tabela SET campo = ? WHERE id = ?;
    public function update($dados){ // Corrigido: nome da função ajustado de 'uptade' para 'update'
        $campos = "";
        $vetorData = [];
        $sep = "";

        foreach($dados as $campo => $valor){
            if ($campo !== 'id'){
                $campos .= $sep . "$campo = ?";
                $vetorData[] = $valor;
                $sep = ", ";
            }
        }
        
        // Corrigido: Adicionamos o ID no final do vetor porque a query exige o ID no WHERE
        $vetorData[] = $dados['id']; 
        $sql = "UPDATE $this->table_name SET $campos WHERE id = ?;";

        try{
            $st = $this->conn->prepare($sql);
            $st->execute($vetorData);
        }catch(PDOException $e){
            throw new Exception("Erro ao atualizar: " . $e->getMessage());
        }
    }
}