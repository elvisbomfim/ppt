<div class="row page-titles">

    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Coberturas</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Coberturas</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
    <h4 class="c-grey-900 mB-20"> <button class="btn btn-success j_action pull-right" data-callback="coberturas" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            
        
    </div>

</div>

<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <h4 class="c-grey-900 mB-20">Coberturas <button class="btn btn-primary j_action"  data-callback="coberturas" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
                    <table id="coberturasTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Coberturas</th>
                                <th>Preço</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->ExeRead('coberturas', "ORDER BY cobertura_id DESC");

                            foreach ($read->getResult() as $value):
                                extract($value);
                                if (empty($cobertura_nome)):
                                $Delete = new Delete;
                                $Delete->ExeDelete('coberturas', "WHERE cobertura_id =:id", "id={$cobertura_id}");
                                else:
                                    $cobertura_preco_kg = number_format($cobertura_preco_kg, 2, ',', '.');
                                ?>
                            

                                <tr id="<?= $cobertura_id; ?>">
                                    <td><?= $cobertura_nome; ?></td>
                                    <td><?= $cobertura_preco_kg; ?></td>
                                    <td><?= $cobertura_status == 0 ? 'Inativo':'Ativo'; ?></td>
                                    <td><button class="btn btn-warning j_action" data-callback="coberturas" data-callback_action="manager" data-id="<?= $cobertura_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="coberturas" data-callback_action="delete" data-id="<?= $cobertura_id; ?>" data-name="<?= $cobertura_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-trash-o"></i> Apagar</button></td>
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
<div class="modal fade" id="coberturasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Coberturas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="coberturas">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
                    <div class="form-group">
                        <label>Nome da cobertura:</label>
                        <input type="text" name="cobertura_nome" class="form-control" id="cobertura_nome"  >
                    </div>
                    <div class="form-group">
                        <label>Preço por kg:</label>
                        <input type="text" name="cobertura_preco_kg" class="form-control money fix_bug_mask" id="cobertura_preco_kg"  >
                    </div>
                    <div class="form-group">
                        <label>Status:</label></br>
                        <label class="radio-inline">
                            <input type="radio" name="cobertura_status"  id="cobertura_status_1" value="1" checked> Ativo
                        </label>
                        <label class="radio-inline"> 
                            <input type="radio" name="cobertura_status" id="cobertura_status_0" value="0"> Inativo
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