

<div class="row">
    <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">
            <h4 class="c-grey-900 mB-20">Bolos <button class="btn btn-primary j_action" data-callback="categorias_bolos" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Bolos</th>
                        <th>Preço</th>
                        <th>Preço Kit festa</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $read = new Read;

                    $read->ExeRead('categoria_bolos', "ORDER BY categoria_bolo_id DESC");

                    foreach ($read->getResult() as $value):
                        extract($value);
                        ?>

                        <?php
                        if (empty($categoria_bolo_nome)):
                            $Delete = new Delete;
                            $Delete->ExeDelete('categoria_bolos', 'WHERE categoria_bolo_id =:id', "id={$categoria_bolo_id}");
                        else:

                            $categoria_bolo_preco_kg = number_format($categoria_bolo_preco_kg, 2, ',', '.');
                            $categoria_bolo_kit_festa = number_format($categoria_bolo_kit_festa, 2, ',', '.');
                            ?>

                            <tr id="<?= $categoria_bolo_id; ?>">
                                <td><?= $categoria_bolo_nome; ?></td>
                                <td><?= $categoria_bolo_preco_kg; ?></td>
                                <td><?= $categoria_bolo_kit_festa; ?></td>
                                <td><?= $categoria_bolo_status == 0 ? 'Inativo' : 'Ativo'; ?></td>
                                <td><button class="btn btn-warning j_action" data-callback="categorias_bolos" data-callback_action="manager" data-id="<?= $categoria_bolo_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="categorias_bolos" data-callback_action="delete" data-id="<?= $categoria_bolo_id; ?>" data-name="<?= $categoria_bolo_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-trash-o"></i> Apagar</button></td>
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


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bolos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="categorias_bolos">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
                    <div class="form-group">
                        <label>Nome do bolo:</label>
                        <input type="text" name="categoria_bolo_nome" class="form-control" id="categoria_bolo_nome"  >
                    </div>
                    <div class="form-group">
                        <label>Preço por kg:</label>
                        <input type="text" name="categoria_bolo_preco_kg" class="form-control money fix_bug_mask" id="categoria_bolo_preco_kg"  >
                    </div>
                    <div class="form-group">
                        <label>Preço kit festa:</label>
                        <input type="text" name="categoria_bolo_kit_festa" class="form-control money fix_bug_mask" id="categoria_bolo_kit_festa"  >
                    </div>
                    <div class="form-group">
                        <label>Foto:</label>
                        <input type="image" name="categoria_bolo_foto" class="form-control " id="categoria_foto"  >
                    </div>
                    <div class="form-group">
                        <label>Status:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="categoria_bolo_status"  id="categoria_bolo_status_1" value="1" checked> Ativo
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="categoria_bolo_status" id="categoria_bolo_status_0" value="0"> Inativo
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