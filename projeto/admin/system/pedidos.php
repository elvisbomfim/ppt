<div class="row">
    <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">
            <h4 class="c-grey-900 mB-20">Pedidos <button class="btn btn-primary btn-add-novo-pedido" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>Data Criação</th>
                        <th>Data Retirada</th>
                        <th>Total do Pedido</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    $read = new Read;

                    $read->FullRead("SELECT * FROM pedidos p "
                            . "INNER JOIN pedidos_bolo b ON b.pedido_id = p.pedido_id");

                    foreach ($read->getResult() as $value):
                        extract($value);
                        ?>

                        <?php
//                        if (empty($categoria_bolo_nome)):
//                            $Delete = new Delete;
//                            $Delete->ExeDelete('categoria_bolos', 'WHERE categoria_bolo_id =:id', "id={$categoria_bolo_id}");
//                        else:
//                            $categoria_bolo_preco_kg = number_format($categoria_bolo_preco_kg, 2, ',', '.');
//                            $categoria_bolo_kit_festa = number_format($categoria_bolo_kit_festa, 2, ',', '.');
                        ?>

                        <tr id="<?= $pedido_id; ?>">
                            <td><?= $pedido_id; ?></td>
                            <td><?= $cliente_nome; ?></td>
                            <td><?= $pedido_data_criacao; ?></td>
                            <td><?= $pedido_data_retirada; ?></td>
                            <td><?= $pedido_total ?></td>
                            <td><button class="btn btn-warning j_action" data-callback="pedidos" data-callback_action="manager" data-id="<?= $categoria_bolo_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="pedidos" data-callback_action="delete" data-id="<?= $categoria_bolo_id; ?>" data-name="<?= $categoria_bolo_nome; ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-ban"></i> Cancelar</button></td>
                        </tr>
                        <?php
                        //   endif;
                    endforeach;
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form class="auto_save" id="pedidos-form" action="" method="post">
                    <input type="hidden" name="callback" value="pedidos">
                    <input type="hidden" id="callback_action" name="callback_action" value="manager">
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
                                    <a class="nav-link" id="torta-tab" data-toggle="tab" href="#torta" role="tab" aria-controls="torta" aria-selected="false">Torta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="salgado-tab" data-toggle="tab" href="#salgado" role="tab" aria-controls="salgado" aria-selected="false">Salgado</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="doce-tab" data-toggle="tab" href="#doce" role="tab" aria-controls="doce" aria-selected="false">Doce</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="refrigerante-tab" data-toggle="tab" href="#refrigerante" role="tab" aria-controls="refrigerante" aria-selected="false">Refrigerante</a>
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
                                                <input type="text" name="categoria_bolo_nome" class="form-control" id="categoria_bolo_nome"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="categoria_bolo_valor" class="form-control" id="categoria_bolo_valor"  >
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label>Massa:</label>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="pedido_bolo_massa"  id="categoria_bolo_status_1" value="0" checked> Branca
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" name="pedido_bolo_massa" id="categoria_bolo_status_0" value="1"> Chocolate
                                            </label>
                                        </div>                                       
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
                                        <input type="text" name="pedido_bolo_papel_arroz" class="form-control" id="pedido_bolo_papel_arroz"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Cores:</label>
                                        <input type="text" name="pedido_bolo_cores" class="form-control" id="pedido_bolo_cores"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Escrita:</label>
                                        <input type="text" name="pedido_bolo_escrita" class="form-control" id="pedido_bolo_escrita"  >
                                    </div>

                                    <div class="form-group">
                                        <label>Observações:</label>
                                        <textarea class="form-control" name="pedido_bolo_observacoes"></textarea>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="torta" style="padding-top:20px; " role="tabpanel" aria-labelledby="torta-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_torta"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas" style="display: none">

                                        <?php
                                        $Read->ExeRead('categoria_tortas', " WHERE categoria_torta_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Categoria:</label>
                                                <select class="form-control" name="tortas[0][categoria_torta_id]">
                                                    <option selected="" disabled="">Selecione a categoria</option>
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='$categoria_torta_id'>{$categoria_torta_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Peso:</label>
                                                <input type="text" name="tortas[0][pedido_torta_peso]" class="form-control" id="pedido_torta_peso"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="tortas[0][pedido_torta_valor]" class="form-control" id="pedido_torta_valor"  >
                                            </div>
                                        </div>
                                        <button class='btn btn-danger remover_campo excluir-torta'><i class='fa fa-close'></i></button>

                                    </div>

                                </div>
                                <div class="tab-pane fade" id="salgado" style="padding-top:20px; " role="tabpanel" aria-labelledby="salgado-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_salgado"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas" style="display: none">

                                        <?php
                                        $Read->ExeRead('salgados');
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Salgado:</label>
                                                <select name="salgados[salgado_id][]" class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='{$salgado_id}'>{$salgado_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" name="salgados[pedido_salgado_qtd][]" class="form-control" id="pedido_salgado_qtd"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor unidade:</label>
                                                <input type="text" name="salgados[salgado_valor_unidade][]" class="form-control" id="salgado_valor_unidade"  >
                                            </div>
                                        </div>
                                        <button class='btn btn-danger remover_campo excluir-salgado'><i class='fa fa-close'></i></button>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="doce" style="padding-top:20px; " role="tabpanel" aria-labelledby="doce-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_docinho"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas" style="display: none">

                                        <?php
                                        $Read->ExeRead('docinhos', " WHERE docinho_status = 1");
                                        ?>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Doces:</label>
                                                <select name="doces[docinho_id][]" class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='{$docinho_id}'>{$docinho_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Qtd:</label>
                                                <input type="number" name="doces[pedido_docinho_qtd][]" class="form-control" id="pedido_docinho_qtd"  >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" class="form-control" id="pedido_docinho_valor_unidade"  readonly="readonly" >
                                            </div>
                                        </div>
                                      
                                        <button class='btn btn-danger remover_campo excluir-docinho'><i class='fa fa-close'></i></button>
                    
                                    </div>
                                    <hr>
                                </div>
                                <div class="tab-pane fade" id="refrigerante" style="padding-top:20px; " role="tabpanel" aria-labelledby="refrigerante-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_refrigerante"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas" style="display: none">

                                        <?php
                                        $Read->ExeRead('refrigerantes', " WHERE refrigerante_status = 1");
                                        ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Refrigerante:</label>
                                                <select name="refrigerantes[refrigerante_id][]" class="form-control">
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='{$refrigerante_id}'>{$refrigerante_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" name="refrigerantes[pedido_refrigerante_qtd][]" class="form-control" id="pedido_refrigerante_qtd"  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Valor unidade:</label>
                                                <input type="text" name="refrigerantes[pedido_refrigerante_valor_unidade][]" class="form-control" id="pedido_refrigerante_valor_unidade"  >
                                            </div>
                                        </div>

                                        <button class='btn btn-danger remover_campo excluir-refrigerante'><i class='fa fa-close'></i></button>

                                    </div>

                                </div>
                                
                                
                            </div>
                        </div>

                        <div class="col-md-2">
                            <h2>Totais:</h2>
                            <div class="form-group">
                                <label>Bolo:</label>
                                <input type="text" name="pedido_bolo_valor_total" class="form-control" id="pedido_bolo_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Doce:</label>
                                <input type="text" name="pedido_docinho_valor_total" class="form-control" id="pedido_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Refrigerante:</label>
                                <input type="text" name="pedido_refrigerante_valor_total" class="form-control" id="pedido_refrigerante_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Salgado:</label>
                                <input type="text" name="pedido_salgado_valor_total" class="form-control" id="pedido_salgado_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Torta:</label>
                                <input type="text" name="pedido_torta_valor_total" class="form-control" id="pedido_torta_valor_total"  readonly="readonly" >
                            </div>

                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" name="pedido_total" class="form-control" id="pedido_total"  readonly="readonly">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    Kit festa</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-fechar-modal" data-dismiss="modal"><i class="fa fa-close"></i> Fechar</button>
                <button type="submit" class="btn btn-primary btn-action-name cadastrar-pedido" form="pedidos-form">Cadastrar</button>
            </div>
        </div>
    </div>
</div>