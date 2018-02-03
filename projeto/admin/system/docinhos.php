<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <h4 class="c-grey-900 mB-20">Doces <button class="btn btn-primary j_action"  data-callback="docinhos" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
                    <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Doces</th>
                                <th>Preço</th>
                                <th>Preço Kit festa</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->ExeRead('docinhos', "ORDER BY docinho_id DESC");

                            foreach ($read->getResult() as $value):
                                extract($value);
                                if (empty($docinho_nome)):
                                    $Delete = new Delete;
                                    $Delete->ExeDelete('docinhos', "WHERE docinho_id =:id", "id={$docinho_id}");
                                else:
                                    $docinho_preco = number_format($docinho_preco, 2, ',', '.');
                                    $docinho_preco_kit_festa = number_format($docinho_preco_kit_festa, 2, ',', '.');
                                    ?>


                                    <tr id="<?= $docinho_id; ?>">
                                        <td><?= $docinho_nome; ?></td>
                                        <td><?= $docinho_preco; ?></td>
                                        <td><?= $docinho_preco_kit_festa; ?></td>
                                        <td><?= $docinho_status == 0 ? 'Inativo' : 'Ativo'; ?></td>
                                        <td><button class="btn btn-warning j_action" data-callback="docinhos" data-callback_action="manager" data-id="<?= $docinho_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="docinhos" data-callback_action="delete" data-name="<?= $docinho_nome; ?>" data-id="<?= $docinho_id; ?>" data-name="<?= $docinho_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-trash-o"></i> Apagar</button></td>
                                    </tr>
                                <?php
                                endif;
                            endforeach;
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Doces</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="docinhos">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
                    <div class="form-group">
                        <label>Nome do doce:</label>
                        <input type="text" name="docinho_nome" class="form-control" id="docinho_nome"  >
                    </div>
                    <div class="form-group">
                        <label>Preço por kg:</label>
                        <input type="text" name="docinho_preco" class="form-control money fix_bug_mask" id="docinho_preco"  >
                    </div>
                    <div class="form-group">
                        <label>Preço kit festa:</label>
                        <input type="text" name="docinho_preco_kit_festa" class="form-control money fix_bug_mask" id="docinho_preco_kit_festa"  >
                    </div>
                    <div class="form-group">
                        <label>Status:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="docinho_status"  value="1" checked> Ativo
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="docinho_status"  value="0"> Inativo
                        </label>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
                <button type="submit" class="btn btn-primary btn-action-name" form="formulario">Cadastrar</button>
            </div>
        </div>
    </div>
</div>