<?php 
    $pessoas = [
        ['nome'=>"Arthur Brenan", 'idade'=>38],
        ['nome'=>"Ryan Bortolossi", 'idade'=>38]
        ];

    foreach($pessoas as $key => $item){
        $nome = $item['nome'];
        $idade = $item['idade'];
        echo "Indice: $key Nome: $nome Idade: $idade<br>";
    }
?>