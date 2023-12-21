<?php

use classdatabase\Database;

require_once('header.php');

/// Incluindo os arquivos necessários

require_once('config.php');
require_once('libraries/database.php');

$contatos = NULL;
$total_contatos = 0;
$search = NULL;
$database = new Database(MYSQL_CONFIG);

/// Verificar se existe algum Post

if($_SERVER['REQUEST_METHOD']== 'POST'){

    /// Procurar por resultados 
    $search = $_POST['text_search'];
    $params = [
        ':search' => '%'. $search .'%'
    ];

    $results = $database->execute_query("SELECT * FROM contactos WHERE nome LIKE :search or telefone LIKE :search ORDER BY id DESC",$params);

} else {
    $results = $database->execute_query("SELECT * FROM contactos ORDER BY id DESC");
}

$contatos = $results ->results;
$total_contatos = $results->affected_rows;


?>

<!-- Procurar e Adicionar -->
<div class="row align-items-center mb-3">
    <div class="col">

        <form action="index.php" method="post">
            <div class="row">
                <div class="col-auto"><input type="text" class="form-control" name="text_search" id="text_search" minlength="3" maxlength="20" required></div>
                <div class="col-auto"><input type="submit" class="btn btn-outline-dark" value="Procurar"></div>
                <div class="col-auto"><a href="index.php" class="btn btn-outline-dark">Ver tudo</a></div>
            </div>
        </form>

    </div>

    <div class="col text-end">
        <a href="adicionar_contato.php" class="btn btn-outline-dark">Adicionar contato</a>
    </div>
</div>

<!-- Mostrar Tabela de Contatos -->
<div class="row">
    <div class="col">


        <?php if($total_contatos == 0): ?>
            <!-- Sem resultados -->
            <p class="text-center opacity-75 mt-3">Não foram encontrados contatos registrados.</p>
            <?php else: ?>
                <!-- Com resultados -->
                <table class="table table-sm table-striped table-bordered">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th width="40%">Nome</th>
                            <th width="30%">Telefone</th>
                            <th width="15%"></th>
                            <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($contatos as $contato): ?>
                        <tr>
                            <td><?= $contato->nome ?></td>
                            <td><?= $contato->telefone ?></td>
                            <td class="text-center"><a href="editar_contato.php?id=<?= $contato->id ?>">Editar</a></td>
                            <td class="text-center"><a href="eliminar_contato.php?id=<?= $contato->id ?>">Eliminar</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                        <!-- Todos os resultados-->
                        <div class="row">
                            <div class="col">
                                <p>Total: <strong><?= $total_contatos ?></strong></p>
                            </div>
                        </div>
             <?php endif; ?>

    </div>
</div>

<?php
require_once('footer.php');
?>