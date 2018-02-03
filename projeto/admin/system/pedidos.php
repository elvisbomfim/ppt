

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

<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bolos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="" method="post">
                    <input type="hidden" name="callback" value="pedidos">
                    <input type="hidden" name="callback_action" value="manager">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nome do Cliente:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Data de criação:</label>
                                <input type="date" name="pedido_data_criacao" class="form-control" id="pedido_data_criacao"  >
                            </div>
                            <div class="form-group">
                                <label>Data de retirada:</label>
                                <input type="date" name="pedido_data_retirada" class="form-control" id="pedido_data_retirada"  >
                            </div>

                        </div>
                        <div class="col-md-7">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="bolo-tab" data-toggle="tab" href="#bolo" role="tab" aria-controls="bolo" aria-selected="true">Bolo</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="doce-tab" data-toggle="tab" href="#doce" role="tab" aria-controls="doce" aria-selected="false">Doce</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="refrigerante-tab" data-toggle="tab" href="#refrigerante" role="tab" aria-controls="refrigerante" aria-selected="false">Refrigerante</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="salgado-tab" data-toggle="tab" href="#salgado" role="tab" aria-controls="salgado" aria-selected="false">Salgado</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="torta-tab" data-toggle="tab" href="#torta" role="tab" aria-controls="torta" aria-selected="false">Torta</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" style="padding-top:20px; " id="bolo" role="tabpanel" aria-labelledby="bolo-tab">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Categoria:</label>
                                                <?php
                                                $Read = new Read;

                                                $Read->ExeRead('categoria_bolos', " WHERE categoria_bolo_status = 1");
                                                ?>
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$categoria_bolo_nome}</option>");
                                                    endforeach;
                                                    ?>                                         
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Peso:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label>Massa:</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="pedido_bolo_massa"  id="categoria_bolo_status_1" value="1" checked> Ativo
                                        </label>
                                        <label class="radio-inline"> 
                                            <input type="radio" name="categoria_bolo_status" id="categoria_bolo_status_0" value="0"> Inativo
                                        </label>
                                    </div>
                                    <label>Recheio:</label>
                                    <div class="row">
                                        <?php
                                        $Read = new Read;

                                        $Read->ExeRead('recheios', " WHERE recheio_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$recheio_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$recheio_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$recheio_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Papel arroz:</label>
                                        <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Cores:</label>
                                        <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Escrita:</label>
                                        <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Observações:</label>
                                        <textarea class="form-control" name="pedido_bolo_observacoes"></textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="doce" style="padding-top:20px; " role="tabpanel" aria-labelledby="doce-tab">
                                    <button class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="row">

                                        <?php
                                        $Read->ExeRead('docinhos', " WHERE docinho_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Doces:</label>
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$docinho_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor unidade:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="refrigerante" style="padding-top:20px; " role="tabpanel" aria-labelledby="refrigerante-tab">

                                    <div class="row">

                                        <?php
                                        $Read->ExeRead('refrigerantes', " WHERE refrigerante_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Refrigerante:</label>
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$refrigerante_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor unidade:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="salgado" style="padding-top:20px; " role="tabpanel" aria-labelledby="salgado-tab">

                                    <div class="row">

                                        <?php
                                        $Read->ExeRead('salgados')
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Salgado:</label>
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$refrigerante_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor unidade:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="torta" style="padding-top:20px; " role="tabpanel" aria-labelledby="torta-tab">

                                    <div class="row">

                                        <?php
                                        $Read->ExeRead('categoria_tortas', " WHERE categoria_bolo_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Categoria:</label>
                                                <select class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option>{$categoria_torta_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Peso:</label>
                                                <input type="number" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Bolo:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Doce:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Refrigerante:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Salgado:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Torta:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>

                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Kit festa</label>
                            </div>
                        </div>
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