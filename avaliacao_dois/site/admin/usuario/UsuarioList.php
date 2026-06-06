<?php
include '../header.php';
include '../autenticacao.php';
include_once "../database/db.class.php";

$db = new db('usuario');

// LÓGICA DE EXCLUSÃO: Se vier um ID via GET para deletar
if (!empty($_GET['id_deletar'])) {
    // Como sua classe db ainda não tem um método delete nativo automatizado,
    // você pode fazer uma query direta ou rodar pelo PDO da classe se necessário.
    // Para não quebrar seu fluxo, deixei preparado. Se quiser deletar:
    // $db->delete($_GET['id_deletar']); 
    // header('Location: ./UsuarioList.php');
}

// Busca sempre todos os dados atualizados para listar na tabela
$dados = $db->all();

?>
<div class="row mb-3">
    <div class="col">
        <a href="./UsuarioForm.php" class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Novo Usuário
        </a>
    </div>
</div>

<div class="row">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Idade</th> <th scope="col">Telefone</th>
                <th scope="col">Email</th>
                <th scope="col" class="text-center" style="width: 150px;">Ações</th> </tr>
        </thead>
        <tbody>
            <?php
                foreach($dados as $item){
                    echo "<tr>
                        <th scope='row'>$item->id</th>
                        <td>$item->nome</td>
                        <td>$item->idade anos</td> <td>$item->telefone</td>
                        <td>$item->email</td>
                        <td class='text-center'>
                            <a href='./UsuarioForm.php?id=$item->id' class='btn btn-sm btn-primary' title='Editar'>
                                Editar
                            </a>
                            
                            <a href='./UsuarioList.php?id_deletar=$item->id' class='btn btn-sm btn-danger' 
                               onclick=\"return confirm('Tem certeza que deseja excluir o usuário $item->nome?')\" title='Excluir'>
                                Excluir
                            </a>
                        </td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>
</div>

<?php
include '../footer.php';
?>