<?php

use classdatabase\Database;

require_once('header.php');

/// Verificando se foi encontrado um ID
if(empty($_GET['id'])){
    header('Location: index.php');
}

require_once('config.php');
require_once('libraries/database.php');

$id = $_GET['id'];
$database = new Database(MYSQL_CONFIG);
$params = [
    ':id'=> $id
];

    $results = $database->execute_query("SELECT * FROM contactos WHERE :id = id",$params);
    $contato = $results->results[0];

    /// Verificando se foi feito um POST
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $nome = $_POST['text_nome'];
        $telefone = $_POST['text_telefone'];
        $params = [
            ':id'=>$id,
            ':telefone'=>$telefone
        ];

        /// Verificando se existe outro contato com o mesmo número
        $results = $database->execute_query("SELECT id FROM contactos WHERE id <> :id AND telefone = :telefone",$params);

        if($results->affected_rows != 0){
            /// Outro um contato encontrado com o mesmo número
                $erro = 'Já existe um contato registrado com esse número.';
        } else {
            $params = [
                ':id' => $id,
                ':nome'=>$nome,
                ':telefone'=>$telefone
            ];

            $results = $database->execute_non_query("UPDATE contactos SET nome = :nome, telefone = :telefone, updated_at = NOW() WHERE id = :id", $params);

            header('Location: index.php');
        }
    }
?>

<div class="row justify-content-center">
    <div class="col-sm-8 col-md-6 col-10">

        <div class="card p-4">

            <form action="editar_contato.php?id=<?= $id ?>" method="post">
                <p class="text-center"><strong>EDITAR CONTATO</strong></p>
                <div class="mb-3">
                    <label for="text_nome" class="form-label">Nome</label>
                    <input type="text" name="text_nome" id="text_nome" class="form-control" minlength="3" maxlength="50" required value=" <?= $contato->nome ?> ">
                </div>
                <div class="mb-3">
                    <label for="text_telefone" class="form-label">Telefone</label>
                    <input type="text" name="text_telefone" id="text_telefone" class="form-control" minlength="3" maxlength="12" required value=" <?= $contato->telefone ?> ">
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-outline-dark">Cancelar</a>
                    <input type="submit" value="Atualizar" class="btn btn-outline-dark">
                </div>
            </form>

        </div>

        <!-- Mensagem de Erro -->

        <?php if(!empty($erro)): ?>
        <div class="mt-3 alert alert-danger p-2 text-center">
            <?= $erro ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once('footer.php');
?>