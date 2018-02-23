<div class="row page-titles">

    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Recheios</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Recheios</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
    <h4 class="c-grey-900 mB-20"> <button class="btn btn-success j_action pull-right" data-callback="recheios" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            
        
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
                                <th>Recheios</th>
                                <th>Preço</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->ExeRead('recheios', "ORDER BY recheio_id DESC");

                            foreach ($read->getResult() as $value):
                                extract($value);
                                if (empty($recheio_nome)):
                                    $Delete = new Delete;
                                    $Delete->ExeDelete('recheios', "WHERE recheio_id =:id", "id={$recheio_id}");
                                else:
                                    $recheio_preco_kg = number_format($recheio_preco_kg, 2, ',', '.');
                                    ?>


                                    <tr id="<?= $recheio_id; ?>">
                                        <td><?= $recheio_nome; ?></td>
                                        <td><?= $recheio_preco_kg; ?></td>
                                        <td><?= $recheio_tipo == 0 ? 'Normal' : 'Especial'; ?></td>
                                        <td><?= $recheio_status == 0 ? 'Inativo' : 'Ativo'; ?></td>
                                        <td><button class="btn btn-warning j_action" data-callback="recheios" data-callback_action="manager" data-id="<?= $recheio_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="recheios" data-callback_action="delete" data-id="<?= $recheio_id; ?>" data-name="<?= $recheio_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-trash-o"></i> Apagar</button></td>
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
                <h5 class="modal-title" id="exampleModalLabel">Recheios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="recheios">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
                    <div class="form-group">
                        <label>Nome do recheio:</label>
                        <input type="text" name="recheio_nome" class="form-control" id="recheio_nome"  >
                    </div>
                    <div class="form-group">
                        <label>Preço por kg:</label>
                        <input type="text" name="recheio_preco_kg" class="form-control money fix_bug_mask" id="recheio_preco_kg"  >
                    </div>
                    <div class="form-group">

                        <label>Tipo:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="recheio_tipo"  id="recheio_tipo_0" value="0" checked> Normal
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="recheio_tipo" id="recheio_tipo_1" value="1"> Especial
                        </label>
                    </div>
                    <div class="form-group">
                        <label>Status:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="recheio_status"  id="recheio_status_1" value="1" checked> Ativo
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="recheio_status" id="recheio_status_0" value="0"> Inativo
                        </label>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
                <button type="submit" class="btn btn-primary btn-action-name" form="formulario" >Cadastrar</button>
            </div>
        </div>
    </div>
</div>