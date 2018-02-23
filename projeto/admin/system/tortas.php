<div class="row page-titles">

    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Tortas</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Tortas</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
    <h4 class="c-grey-900 mB-20"> <button class="btn btn-success j_action pull-right" data-callback="categorias_tortas" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            
        
    </div>

</div>

<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Tortas</th>
                                <th>Preço</th>
                                <th>Preço Kit festa</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->ExeRead('categoria_tortas', "ORDER BY categoria_torta_id DESC");

                            foreach ($read->getResult() as $value):
                                extract($value);
                                if (empty($categoria_torta_nome)):
                                    $Delete = new Delete;

                                    $Delete->ExeDelete('categoria_tortas', 'WHERE categoria_torta_id =:id', "id={$categoria_torta_id}");
                                else:
                                    ?>


                                    <tr id="<?= $categoria_torta_id; ?>">
                                        <td><?= $categoria_torta_nome; ?></td>
                                        <td><?= $categoria_torta_preco_kg; ?></td>
                                        <td><?= $categoria_torta_kit_festa; ?></td>
                                        <td><?= $categoria_torta_status == 0 ? 'Inativo' : 'Ativo'; ?></td>
                                        <td><button class="btn btn-warning j_action" data-callback="categorias_tortas" data-callback_action="manager" data-id="<?= $categoria_torta_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="categorias_tortas" data-callback_action="delete" data-id="<?= $categoria_torta_id; ?>" data-name="<?= $categoria_torta_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-trash-o"></i> Apagar</button></td>
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
                <h5 class="modal-title" id="exampleModalLabel">Tortas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="categorias_tortas">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
                    <div class="form-group">
                        <label>Nome da categoria_torta:</label>
                        <input type="text" name="categoria_torta_nome" class="form-control" id="categoria_torta_nome"  >
                    </div>
                    <div class="form-group">
                        <label>Preço por kg:</label>
                        <input type="text" name="categoria_torta_preco_kg" class="form-control money fix_bug_mask" id="categoria_torta_preco_kg"  >
                    </div>
                    <div class="form-group">
                        <label>Preço kit festa:</label>
                        <input type="text" name="categoria_torta_kit_festa" class="form-control money fix_bug_mask" id="categoria_torta_kit_festa"  >
                    </div>
                    <div class="form-group">
                        <label>Status:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="categoria_torta_status"  id="categoria_torta_status_1" value="1" checked> Ativo
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="categoria_torta_status" id="categoria_torta_status_0" value="0"> Inativo
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