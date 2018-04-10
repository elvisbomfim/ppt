<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Pedidos</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Pedidos</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
        <button class="btn btn-success btn-add-novo-pedido pull-right get_action_name" data-action-name="create" data-toggle="modal" data-target="#pedidosModal"><i class="fa fa-plus"></i> Cadastrar novo</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <table id="pedidosTable" class="tablePPT table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nº Pedido</th>
                                <th>Cliente</th>
                                <th>Data Criação</th>
                                <th>Data Retirada</th>
                                <th>Total do Pedido</th>
                                <th>Status</th>
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
                                $pedido_total = number_format($pedido_total, 2, ',', '.');
//                            $categoria_bolo_kit_festa = number_format($categoria_bolo_kit_festa, 2, ',', '.');
                                ?>

                                <tr id="<?= $pedido_id; ?>">
                                    <td>P<?= $pedido_id; ?></td>
                                    <td><?= $cliente_nome; ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($pedido_data_criacao)); ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($pedido_data_retirada)); ?></td>
                                    <td><?= $pedido_total ?></td>
                                    <?php 
                                        switch ($pedido_status):
                                            case '0':
                                                break;
                                            case '1':
                                                break;
                                            case '2':
                                                break;
                                            case '3':
                                                break;
                                        endswitch;
                                    ?>
                                    
                                    <td><?= $pedido_status ?></td>
                                    <td>
                                        <button class="btn btn-warning j_action get_action_name" title="Editar Pedido" data-action-name="update" data-callback="pedidos" data-callback_action="manager" data-id="<?= $pedido_id ?>"><i class="fa fa-edit"></i></button> 
                                        <button class="btn btn-primary j_action" title="Duplicar Pedido" data-callback="pedidos" data-callback_action="duplicar" data-id="<?= $pedido_id ?>"><i class="fa fa-copy"></i> </button>
                                        <button class="btn btn-danger" title="Cancelar Pedido"  data-callback="pedidos" data-callback_action="cancelar" data-id="<?php // $categoria_bolo_id;                  ?>" data-name="<?php // $categoria_bolo_nome;                  ?>" data-toggle="modal" data-target="#confirmar-cancelar"><i class="fa fa-ban"></i></button> 
                                    </td>
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
    </div>

</div>


<!-- Modal -->
<div class="modal fade modal-pedidos" id="pedidosModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    <div class="get_id"></div>
                    <div style="display:none;" class="action_name"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nome do Cliente:  </label>
                                        <br>
                                        <select id="cliente_nome_select" required="" name="cliente_id" class="cliente_nome_id form-control selects-pedidos" style="width: 100%"></select>
                                        <button type="button" class="btn btn-primary j_action" data-callback="clientes" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de criação:</label>
                                        <?php
                                        $date_now = date('Y-m-d H:i');
                                        $datetime = new DateTime($date_now);
                                        ?>
                                        <input type="datetime-local" value="<?= $datetime->format('Y-m-d\TH:i') ?>" name="pedido_data_criacao" class="form-control pedido_data_criacao" required="" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de retirada:</label>
                                        <input type="datetime-local" name="pedido_data_retirada" class="form-control pedido_data_retirada"  required="" >
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
                                    <button class="btn btn-primary pull-right add_field add_bolo" id="add_bolo"><i class="fa fa-plus"></i> Acrescentar</button>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div id="accordion">
                                        <div class="nova_lista"></div>
                                        <div class="card listas">
                                            <div class="card-header" id="heading-0">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse-0" aria-expanded="true" aria-controls="collapse-0">
                                                        Bolo #1
                                                    </button>

                                                    <button class='btn btn-sm btn-danger remover_campo pull-right excluir-bolo' data-id-button="0" ><i class='fa fa-close'></i> </button>
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
                                                                <select class="form-control categoria_bolo_id selects-pedidos" name="bolos[0][categoria_bolo_id]">
                                                                    <option selected="" disabled="" value="">Selecione a categoria</option>
                                                                    <?php
                                                                    foreach ($Read->getResult() as $value):
                                                                        extract($value);
                                                                        echo("<option value='{$categoria_bolo_id}' >{$categoria_bolo_nome}</option>");
                                                                    endforeach;
                                                                    ?>                                         
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Peso:</label>
                                                                <input type="number" min="1" value="1" name="bolos[0][pedido_bolo_peso]" class="form-control pedido_bolo_peso" >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label>Valor:</label>
                                                                <input type="text" name="bolos[0][pedido_bolo_valor]" class="form-control pedido_bolo_valor" readonly="readonly" >
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label>Massa:</label>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="bolos[0][pedido_bolo_massa]" value="0" checked> Branca
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label">
                                                                <input type="radio" name="bolos[0][pedido_bolo_massa]"  value="1"> Chocolate
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
                                                        <div class="col-md-6">
                                                            <?php
                                                            foreach ($array[0] as $value):
                                                                extract($value);
                                                                ?>
                                                                <div class="form-check">

                                                                    <label class="form-check-label form-check-input" for="exampleCheck1"><input type="checkbox" name="bolos[0][recheio_comum][]" value="<?= $recheio_id ?>">
                                                                        <?= $recheio_nome ?></label>
                                                                </div>


                                                                <?php
                                                            endforeach;
                                                            ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <?php
                                                            foreach ($array[1] as $value):
                                                                extract($value);
                                                                ?>

                                                                <div class="form-check">

                                                                    <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" name="bolos[0][recheio_comum][]" value="<?= $recheio_id ?>">
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
                                                                <select class="form-control selects-pedidos pedido_bolo_recheio_especial1" name="bolos[0][recheio_especial][]" >
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
                                                                <select class="form-control pedido_bolo_recheio_especial2 selects-pedidos" name="bolos[0][recheio_especial][]" >
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
                                                                <select class="form-control pedido_bolo_recheio_especial3 selects-pedidos" name="bolos[0][recheio_especial][]">
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
                                                                <input type="text"  name="bolos[0][pedido_bolo_papel_arroz]" class="form-control pedido_bolo_papel_arroz"  >
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Cores:</label>
                                                                <input type="text" name="bolos[0][pedido_bolo_cores]" class="form-control pedido_bolo_cores"  >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Escrita:</label>
                                                        <input type="text" name="bolos[0][pedido_bolo_escrita]" class="form-control pedido_bolo_escrita"  >
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Observações:</label>
                                                        <textarea class="form-control pedido_bolo_observacoes" name="bolos[0][pedido_bolo_observacoes]" ></textarea>
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
                                                <select class="form-control selects-pedidos" name="tortas[0][categoria_torta_id]">
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
                                                <select name="salgados[0][salgado_id]" class="form-control selects-pedidos">
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
                                                <select name="doces[0][docinho_id]" class="form-control selects-pedidos">
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
                                                <select name="refrigerantes[0][refrigerante_id]" class="form-control selects-pedidos">
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
                                <input type="text" name="pedido_bolo_valor_total" class="form-control pedido_bolo_valor_total" id="pedido_bolo_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Torta:</label>
                                <input type="text" name="pedido_torta_valor_total" class="form-control pedido_torta_valor_total" id="pedido_torta_valor_total"  readonly="readonly" >
                            </div>
                            <div class="form-group">
                                <label>Salgado:</label>
                                <input type="text" name="pedido_salgado_valor_total" class="form-control pedido_salgado_valor_total" id="pedido_salgado_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Doce:</label>
                                <input type="text" name="pedido_docinho_valor_total" class="form-control pedido_doce_valor_total" id="pedido_doce_valor_total"  readonly="readonly">
                            </div>
                            <div class="form-group">
                                <label>Refrigerante:</label>
                                <input type="text" name="pedido_refrigerante_valor_total" class="form-control" id="pedido_refrigerante_valor_total"  readonly="readonly">
                            </div>



                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" name="pedido_total" class="form-control pedido_total" id="pedido_total"  readonly="readonly">
                            </div>
                            <div class="form-check">
                                <label class="form-check-label" for="exampleCheck1"><input type="checkbox" class="form-check-input" name="kit_festa" id="kit_festa" value="1">
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

<!-- Modal -->
<div class="modal fade" id="clientesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="clientes">
                    <input type="hidden" name="callback_action" value="update">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome do Cliente:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cpf:</label>
                                <input type="text" name="cliente_cpf" class="form-control cpf fix_bug_mask" id="cliente_cpf"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone:</label>
                                <input type="text" name="cliente_telefone_1" class="form-control phone fix_bug_mask" id="cliente_telefone_1"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Celular:</label>
                                <input type="text" name="cliente_telefone_2" class="form-control phone fix_bug_mask" id="cliente_telefone_2"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data de nascimento:</label>
                                <input type="date" name="cliente_data_nascimento" class="form-control" id="cliente_data_nascimento"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observações:</label>

                                <textarea name="cliente_observacoes" class="form-control" id="cliente_observacoes"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cep: </label>
                                <input type="text"  name="cliente_cep" class="getCep form-control fix_bug_mask" id="cliente_cep">

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" class="form-control logradouro" name="cliente_rua" id="cliente_rua">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" name="cliente_numero" id="cliente_numero">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" class="form-control bairro" name="cliente_bairro" id="cliente_bairro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" class="form-control complemento" name="cliente_complemento" id="cliente_complemento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control cidade" name="cliente_cidade" id="cliente_cidade">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control uf" name="cliente_estado" id="cliente_estado" >
                            </div>
                        </div>
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



