<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <h4 class="c-grey-900 mB-20">Salgados <button class="btn btn-primary"  data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
                    <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Sagado</th>
                                <th>Preço</th>
                                <th>Preço Kit festa</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->ExeRead('salgados');

                            foreach ($read->getResult() as $value):
                                extract($value);
                                ?>


                                <tr id="<?= $salgado_id; ?>">
                                    <td><?= $salgado_nome; ?></td>
                                    <td><?= $salgado_preco; ?></td>
                                    <td><?= $salgado_kit_festa; ?></td>
                                    <td><?= $salgado_status; ?></td>
                                    <td><button class="btn btn-warning j_action" data-callback="salgados" data-callback_action="update" data-id="<?= $salgado_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger j_action"  data-callback="salgados" data-callback_action="delete" data-id="<?= $salgado_id; ?>"><i class="fa fa-trash-o"></i> Apagar</button></td>
                                </tr>
                                <?php
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
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="salgados">
                    <input type="hidden" name="callback_action" value="manager">
                    <div class="form-group">
                        <label>Nome do salgado:</label>
                        <input type="text" name="salgado_nome" class="form-control" id="salgado_nome">
                    </div>
                    <div class="form-group">
                        <label>Preço:</label>
                        <input type="text" name="salgado_preco" class="form-control" id="salgado_preco_">
                    </div>
                    <div class="form-group">
                        <label>Preço kit festa:</label>
                        <input type="text" name="salgado_kit_festa" class="form-control" id="salgado_kit_festa"  >
                    </div>


                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="formulario">Cadastrar</button>
            </div>
        </div>
    </div>
</div>