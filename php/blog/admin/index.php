<?php

include_once './database/db.class.php';

$conn = new db("usuario");

$dados = [
    'nome' => "marisa",
    'telefone' => "49988990099",
    'email' => "marisa@gmail.com"


];

$conn->store($dados);
echo "dados inseridos com sucesso";