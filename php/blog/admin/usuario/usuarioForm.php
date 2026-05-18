<?php
include '../header.php';
include_once "../database/db.class.php";

$db = new db('usuario');

if(!empty($_POST)){
    //var_dump($_POST);
    //exit;
    $db->store($_POST);

    echo "<script>
            setTimeout(()=>window.location.href='./UsuarioList.php',1500);
        </script>";
}

?>



<div class="col">
    <form action="./resultadoFormAluno.php" method="post">
        <div class="col-6">
            <label for="nome">Nome: </label>
            <input type="text" name="nome" class="form-control">
        </div>
        <div class="col-6">
            <label for="email">Email: </label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="col-6">
            <label for="senha">Senha: </label>
            <input type="password" name="senha" class="form-control">
        </div>
        <div class="col-6">
            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" class="form-control">
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-success">Enviar</button>
            <a href="./UsuarioList.php" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>


<?php
include '../footer.php';
?>