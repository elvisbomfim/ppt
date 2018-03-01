<div class="row page-titles">

    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Pedidos</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Pedidos</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <button class="btn btn-success btn-add-novo-pedido pull-right" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i> Cadastrar novo</button>

    </div>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">
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
                            . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id");

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
                            <td><button class="btn btn-warning j_action" data-callback="pedidos" data-callback_action="manager" data-id="<?= $pedido_id ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger"  data-callback="pedidos" data-callback_action="delete" data-id="<?php // $categoria_bolo_id;    ?>" data-name="<?php // $categoria_bolo_nome;    ?>" data-toggle="modal" data-target="#confirmar-apagar"><i class="fa fa-ban"></i> Cancelar</button></td>
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
<div class="modal fade modal-pedidos" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <input type="hidden" id="callback_action" name="callback_action" value="calcular">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nome do Cliente:  </label>
                                        <br>
                                        <select id="cliente_nome_id" required="" name="cliente_id" class="cliente_nome_id form-control" style="width: 100%"></select>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de criação:</label>
                                        <input type="date" name="pedido_data_criacao" class="form-control" id="pedido_data_criacao" required="" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de retirada:</label>
                                        <input type="date" name="pedido_data_retirada" class="form-control" id="pedido_data_retirada" required="" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
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
                                    <button class="btn btn-primary pull-right add_field" id="add_bolo"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div id="accordion">
                                        <div class="nova_lista"></div>
                                        <div class="card listas">
                                            <div class="card-header" id="heading-0">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-0" aria-expanded="true" aria-controls="collapse-0">
                                                        Collapsible Group Item #1
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse-0" class="collapse show" aria-labelledby="heading-0" data-parent="#accordion">
                                                <div class="card-body">

                                                    <div class="row">






                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Categoria:</label>
                                                                <?php
                                                                $Read = new Read;

                                                                $Read->ExeRead('categoria_bolos', " WHERE categoria_bolo_status = 1");
                                                                ?>
                                                                <select class="form-control" name="bolos[0][categoria_bolo_id]" id='categoria_bolo_id'>
                                                                    <option selected="" disabled="" value="">Selecione a categoria</option>
                                                                    <?php
                                                                    foreach ($Read->getResult() as $value):
                                                                        extract($value);
                                                                        echo("<option value='{$categoria_bolo_id}' id='categoria_bolo_id'>{$categoria_bolo_nome}</option>");
                                                                    endforeach;
                                                                    ?>                                         
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Peso:</label>
                                                                <input type="number" min="1" value="1" name="bolos[0][pedido_bolo_peso]" id="pedido_bolo_peso" class="form-control" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Valor:</label>
                                                                <input type="text" pedido_bolo_peso name="bolos[0][pedido_bolo_valor]" id="pedido_bolo_valor" class="form-control" readonly="readonly" >
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Massa:</label>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="bolos[0][pedido_bolo_massa]" id="pedido_bolo_massa" value="0" checked> Branca
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="bolos[0][pedido_bolo_massa]" id="pedido_bolo_massa" value="1"> Chocolate
                                                            </label>
                                                        </div>                                       
                                                    </div>
                                                    <label>Recheios Comuns:</label>
                                                    <?php
                                                    $Read = new Read;

                                                    $Read->ExeRead('recheios', " WHERE recheio_status = 1 AND recheio_tipo = 0");

                                                    $array = array_chunk($Read->getResult(), ($Read->getRowCount() / 2));
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-6" id="recheio_comum_1">
                                                            <?php
                                                            foreach ($array[0] as $value):
                                                                extract($value);
                                                                ?>
                                                                <div class="form-check">

                                                                    <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" name="bolos[0][recheio_comum][]" value="<?= $recheio_id ?>">
                                                                        <?= $recheio_nome ?></label>
                                                                </div>


                                                                <?php
                                                            endforeach;
                                                            ?>
                                                        </div>
                                                        <div class="col-md-6" id="recheio_comum_2">
                                                            <?php
                                                            foreach ($array[1] as $value):
                                                                extract($value);
                                                                ?>

                                                                <div class="form-check">

                                                                    <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" name="recheio_comum[]" value="<?= $recheio_id ?>">
                                                                        <?= $recheio_nome; ?></label>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                    <label>Recheios Especiais:</label>
                                                    <div class="row">
                                                        <?php
                                                        $Read = new Read;

                                                        $Read->ExeRead('recheios', " WHERE recheio_status = 1 AND recheio_tipo = 1");
                                                        ?>
                                                        <div class="col-md-4">
                                                            <div class="form-group">

                                                                <select class="form-control" name="bolos[0][recheio_especial][zero]" id="recheio_especial_0">
                                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                                    <?php
                                                                    foreach ($Read->getResult() as $value):
                                                                        extract($value);
                                                                        echo("<option value='{$recheio_id}'>{$recheio_nome} R$ " . number_format($recheio_preco_kg, 2, ',', '.') . "</option>");
                                                                    endforeach;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <select class="form-control" name="bolos[0][recheio_especial][um]" id="recheio_especial_1">
                                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                                    <?php
                                                                    foreach ($Read->getResult() as $value):
                                                                        extract($value);
                                                                        echo("<option value='{$recheio_id}'>{$recheio_nome} R$ " . number_format($recheio_preco_kg, 2, ',', '.') . "</option>");
                                                                    endforeach;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <select class="form-control" name="bolos[0][recheio_especial][dois]" id="recheio_especial_2">
                                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                                    <?php
                                                                    foreach ($Read->getResult() as $value):
                                                                        extract($value);
                                                                        echo("<option value='{$recheio_id}'>{$recheio_nome} R$ " . number_format($recheio_preco_kg, 2, ',', '.') . "</option>");
                                                                    endforeach;
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Papel arroz:</label>
                                                                <input type="text" name="bolos[0][pedido_bolo_papel_arroz]" class="form-control" id="pedido_bolo_papel_arroz"  >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Cores:</label>
                                                                <input type="text" name="bolos[0][pedido_bolo_cores]" class="form-control" id="pedido_bolo_cores"  >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Escrita:</label>
                                                        <input type="text" name="bolos[0][pedido_bolo_escrita]" class="form-control" id="pedido_bolo_escrita"  >
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Observações:</label>
                                                        <textarea class="form-control" name="bolos[0][pedido_bolo_observacoes]" id="pedido_bolo_observacoes"></textarea>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!--                                        <div class="card">
                                                                                    <div class="card-header" id="headingOne2">
                                                                                        <h5 class="mb-0">
                                                                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne2" aria-expanded="false" aria-controls="collapseOne2">
                                                                                                Collapsible Group Item #1
                                                                                            </button>
                                                                                        </h5>
                                                                                    </div>
                                        
                                                                                    <div id="collapseOne2" class="collapse" aria-labelledby="headingOne2" data-parent="#accordion">
                                                                                        <div class="card-body">
                                                                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="torta" style="padding-top:20px; " role="tabpanel" aria-labelledby="torta-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_torta"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas">

                                        <?php
                                        $Read->ExeRead('categoria_tortas', " WHERE categoria_torta_status = 1");
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Categoria:</label>
                                                <select class="form-control" name="tortas[0][categoria_torta_id]">
                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='$categoria_torta_id'>{$categoria_torta_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Peso:</label>
                                                <input type="number" min="1" name="tortas[0][pedido_torta_peso]" value="1" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="tortas[0][pedido_torta_valor]"  class="form-control" readonly="readonly">
                                            </div>
                                        </div>
                                        <button class='btn btn-sm btn-danger remover_campo excluir-torta'><i class='fa fa-close'></i> Remover</button>

                                    </div>

                                </div>
                                <div class="tab-pane fade" id="salgado" style="padding-top:20px; " role="tabpanel" aria-labelledby="salgado-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_salgado"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas">

                                        <?php
                                        $Read->ExeRead('salgados');
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salgado:</label>
                                                <select name="salgados[0][salgado_id]" class="form-control">
                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='{$salgado_id}'>{$salgado_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" min="1" name="salgados[0][pedido_salgado_qtd]" value="1" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="salgados[0][pedido_salgado_valor]" class="form-control" readonly="readonly" >
                                            </div>
                                        </div>
                                        <button class='btn btn-sm btn-danger remover_campo excluir-salgado'><i class='fa fa-close'></i> Remover</button>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="doce" style="padding-top:20px; " role="tabpanel" aria-labelledby="doce-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_docinho"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas">

                                        <?php
                                        $Read->ExeRead('docinhos', " WHERE docinho_status = 1");
                                        ?>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Doces:</label>
                                                <select name="doces[0][docinho_id]" class="form-control">
                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
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
                                                <label>Quantidade:</label>
                                                <input type="number" min="1" name="doces[0][pedido_docinho_qtd]" value="1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="doces[0][pedido_docinho_valor_unidade]" class="form-control" readonly="readonly" />
                                            </div>
                                        </div>

                                        <button class='btn btn-sm btn-danger remover_campo excluir-docinho'><i class='fa fa-close'></i> Remover</button>

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="refrigerante" style="padding-top:20px; " role="tabpanel" aria-labelledby="refrigerante-tab">
                                    <button class="btn btn-primary pull-right add_field" id="add_refrigerante"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="nova_lista"></div>
                                    <div class="row listas">

                                        <?php
                                        $Read->ExeRead('refrigerantes', " WHERE refrigerante_status = 1");
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Refrigerante:</label>
                                                <select name="refrigerantes[0][refrigerante_id]" class="form-control">
                                                    <option selected="" value="" disabled="">Selecione a categoria</option>
                                                    <?php
                                                    foreach ($Read->getResult() as $value):
                                                        extract($value);
                                                        echo("<option value='{$refrigerante_id}'>{$refrigerante_nome}</option>");
                                                    endforeach;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Quantidade:</label>
                                                <input type="number" min="1" name="refrigerantes[0][pedido_refrigerante_qtd]" value="1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Valor:</label>
                                                <input type="text" name="refrigerantes[0][pedido_refrigerante_valor_unidade]" class="form-control" readonly="readonly">
                                            </div>
                                        </div>

                                        <button class='btn btn-sm btn-danger remover_campo excluir-refrigerante'><i class='fa fa-close'></i> Remover</button>

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
                                <label>Torta:</label>
                                <input type="text" name="pedido_torta_valor_total" class="form-control" id="pedido_torta_valor_total"  readonly="readonly" >
                            </div>
                            <div class="form-group">
                                <label>Salgado:</label>
                                <input type="text" name="pedido_salgado_valor_total" class="form-control" id="pedido_salgado_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Doce:</label>
                                <input type="text" name="pedido_docinho_valor_total" class="form-control" id="pedido_doce_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Refrigerante:</label>
                                <input type="text" name="pedido_refrigerante_valor_total" class="form-control" id="pedido_refrigerante_valor_total"  readonly="readonly">
                            </div>



                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" name="pedido_total" class="form-control" id="pedido_total"  readonly="readonly">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" name="kit_festa" value="1">
                                    Kit festa</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary btn-fechar-modal" data-dismiss="modal" form="pedidos-form"><i class="fa fa-close"></i> Fechar</button>
                <button type="submit" class="btn btn-primary btn-action-name cadastrar-pedido" form="pedidos-form">Cadastrar</button>
            </div>
        </div>
    </div>
</div>