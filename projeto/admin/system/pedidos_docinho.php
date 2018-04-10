<?php
$Read = new Read;
?>
<!-- Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block">
        <div class="bgc-white bd bdrs-3 p-20 mB-20">
            <table class="table tablePPT table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>N° Pedido do Doce</th>
                                <th>N° Pedido Geral</th>
                                <th>Cliente</th>
                                <th>Ver detalhes</th>
                                <th>Status Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
                                    . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                                    . "LEFT JOIN pedidos_docinho pb ON pb.pedido_id = p.pedido_id "
                                    . "WHERE p.pedido_status != 6");
                            if (!empty($Read->getResult())):

                                foreach ($Read->getResult() as $pedido):

                                    extract($pedido);

                                    if (!empty($pedido_array_docinho)):

                                        $total_docinho = number_format($pedido_docinho_valor_total, 2, ',', '.');

                                        $array_docinho = json_decode($pedido_array_docinho, true);

                                        $i = 0;

                                        $Read->ExeRead('status');
                                        $tabela_status = $Read->getResult();

                                        foreach ($array_docinho as $key => $value):
                                            extract($value);

                                            $dados = "pedidos_docinho,pedido_docinho_id,$pedido_docinho_id,pedido_array_docinho,$key,doce"; //tabela, coluna, id, nome do array, posicao
                                            //  $array = json_encode($array);
                                            ?>
                                            <tr class="active">
                                                <td>D<?= $pedido_docinho_id .'#'.($key+1) ?></td>
                                                <td>P<?= $pedido_id ?></td>
                                                <td><?= $cliente_nome ?></td>
                                                <td><button class="btn btn-primary j_action" data-callback="manager_pedidos" data-callback_action="detalhes"  data-dados="<?= $dados ?>"><i class="fa fa-eye"></i></button></td>
                                                <td>
                                                    <select class="form-control  j_select" data-callback="manager_pedidos" data-callback_action="update-docinho" data-id="<?=$pedido_docinho_id?>" data-key="<?=$key?>">
                                                        <?php
                                                        foreach ($tabela_status as $statusValor):
                                                            extract($statusValor);
                                                        
                                                            ?>
                                                        
                                                            <option value="<?= $status_id; ?>" <?= $docinho_status == $status_id ? 'selected' : '' ?> ><?= $status_nome; ?></option>
                                                            <?php
                                                        endforeach;
                                                        ?>
                                                    </select></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </tbody>
                    </table>
                
            </div>
        </div>
    </div>
</div>
</div>
<!-- Row -->