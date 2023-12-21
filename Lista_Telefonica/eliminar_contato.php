<?php

use classdatabase\Database;

require_once('header.php');
require_once('config.php');
require_once('libraries/database.php');

    /// Verificando se existe um ID 
    if(empty($_GET['id'])){
        header('Location: index.php');
    }

    /// Pegando informações dos contatos
    $id = $_GET['id'];
    $database = new Database(MYSQL_CONFIG);
    $params = [
        ':id'=>$id
    ];


    /// Verificando se o Delete foi executado
    if(empty($_GET['delete'])){
        $results = $database->execute_query("SELECT * FROM contactos WHERE id = :id",$params);
        $contato = $results->results[0];
    } else {
        $results = $database ->execute_non_query("DELETE FROM contactos WHERE id = :id ", $params);
        header('Location: index.php');
    }


?>

<div class="row">
    <div class="col text-center">
        <h3>Deseja eliminar o seguinte contacto?</h3>

        <div class="my-4">
            <div>
                <span class="me-5">Nome: <strong> <?= $contato->nome ?> </strong></span>
                <span>Telefone: <strong> <?= $contato->telefone ?> </strong></span>
            </div>
        </div>

        <a href="index.php" class="btn btn-outline-dark yes-no-width">Não</a>
        <a href="eliminar_contato.php?id=<?= $id ?>&delete=yes" class="btn btn-outline-dark yes-no-width">Sim</a>
    </div>
</div>

<?php
require_once('footer.php');
?>