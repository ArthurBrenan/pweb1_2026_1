<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        echo "arthur é um gostoso<br>";
        $nome = "Arthur";
        $idade = 18;
        $notas = [5,7,10,9];
        echo "<br>$nome tem $idade anos.";
        if ($idade>=18){
            echo '<br>É maior de idade!';
        } else{
            echo '<br>É menor de idade!';
        }
        
        echo "<br>";
        /*print_r($notas); //imprimir vetores*/
        for($i = 0; $i<count($notas); $i++){
            echo $notas[$i]."<br>";
        }

        echo "<br>";
        //diferentemente do if normal, esse é um for dinâmico, não é necessário informar a inicialização, nem o increment.
        foreach($notas as $item){
            echo $item."<br>";
        }

        $nomes = ["Arhur", "Brenan"];
        echo "<br>";
        for($i = 0; $i<count($nomes); $i++){
            echo $nomes[$i]."<br>";
        }

        echo "<br>";
        foreach($nomes as $item){
            echo $item."<br>";
        }

        //vetores associativos
        $carro = ['modelo' => "mustang", 'cor' => "preto"];
        echo $carro['modelo']. " - " .$carro['cor'];


        //aula3

        $carros = [
            ['modelo'=> "Mustang", 'cor' => "Branco", "Ano" => 2026],
            ['modelo'=> "Opala", 'cor' => "Vermelho", "Ano" => 1993],
            ['modelo'=> "Fusca", 'cor' => "Azul", "Ano" => 2003]
        ];
            //inacabado
    ?>

    <p>Meu site sobre <?= $carro['modelo']. " - " .$carro['cor'] ?></p>
    <?php 
    include "./aula02.php";?>
</body>
</html>