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
                                <th>N° Pedido do Bolo</th>
                                <th>N° Pedido Geral</th>
                                <th>Cliente</th>                                
                                <th>Status Pedido</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
                                    . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                                    . "LEFT JOIN pedidos_bolo pb ON pb.pedido_id = p.pedido_id "
                                    . "WHERE p.pedido_status != 6");
                            if (!empty($Read->getResult())):

                                foreach ($Read->getResult() as $pedido):

                                    extract($pedido);

                                    if (!empty($pedido_array_bolo)):

                                        $total_bolo = number_format($pedido_bolo_valor_total, 2, ',', '.');

                                        $array_bolo = json_decode($pedido_array_bolo, true);

                                        $i = 0;

                                        $Read->ExeRead('status');
                                        $tabela_status = $Read->getResult();

                                        foreach ($array_bolo as $key => $value):
                                            extract($value);

                                            $dados = "pedidos_bolo,pedido_bolo_id,$pedido_bolo_id,pedido_array_bolo,$key,bolo"; //tabela, coluna, id, nome do array, posicao
                                            //  $array = json_encode($array);
                                            ?>
                                            <tr class="active">                                                
                                                <td>B<?= $pedido_bolo_id .'#'.($key+1) ?></td>
                                                <td>P<?= $pedido_id ?></td>
                                                <td><?= $cliente_nome ?></td>
                                                
                                                <td>
                                                    
                                                        <?php
                                                        foreach ($tabela_status as $statusValor):
                                                            extract($statusValor);
                                                        
                                                            ?>
                                                            <option value="<?= $status_id; ?>" <?= $bolo_status == $status_id ? 'selected' : '' ?> ><?= $status_nome; ?></option>
                                                            <?php
                                                        endforeach;
                                                        ?>
                                                    </td>
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