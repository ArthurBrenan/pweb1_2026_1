<?php
include '../header.php';
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
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>
</div>


<?php
include '../footer.php';
?>