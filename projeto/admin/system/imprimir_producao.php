<?php
ob_start();
require '../../_app/Config.inc.php';

$GET = filter_input_array(INPUT_GET, FILTER_DEFAULT);

if (empty($GET)):
    die("Nenhum paramento foi passado");
endif;

$Read = new Read;

$Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
        . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
        . "LEFT JOIN pedidos_bolo pb ON pb.pedido_id = p.pedido_id "
        . "LEFT JOIN pedidos_docinho pd ON pd.pedido_id = p.pedido_id "
        . "LEFT JOIN pedidos_refrigerante pr ON pr.pedido_id = p.pedido_id "
        . "LEFT JOIN pedidos_salgado ps ON ps.pedido_id = p.pedido_id "
        . "LEFT JOIN pedidos_torta pt ON pt.pedido_id = p.pedido_id "
        . "LEFT JOIN pedidos_outros po ON po.pedido_id = p.pedido_id "
        . "WHERE p.pedido_id =:id ", "id={$GET['id']}");

extract($Read->getResult()[0]);

$date_now = date('Y-m-d H:i');
$datetime = new DateTime($date_now);

// echo '<pre>';
// var_dump($pedido_array_bolo);
// echo '</pre>';
?>
<style>

   .printer-ticket .td{
        position: relative;
        float: left;
        display: inline-block;
        width: 10%;
    }
  
    .printer-ticket {
        all: initial;
        * {
            all: unset;
        }
    }

    .ttu { text-transform: uppercase; }

    .printer-ticket {
        margin: 0 auto;
        background-color: white;
        padding: 15px;
        display: table !important;
        width: 100%;
        max-width: 400px;
        font-weight: light;
        line-height: 1.3em;



    }

    .printer-ticket .text {
        text-align: center;
    }

    .printer-ticket  .th:nth-child(2) {
        width: 50px;
    }

    .printer-ticket .td:nth-child(3) { 
        width: 90px; text-align: right; 
    }

    .printer-ticket   .th { 
        font-weight: inherit;
        padding: @printer-padding-base 0;
        text-align: center;
        border-bottom: 1px dashed @color-gray;
    }
    .printer-ticket .tbody .tr:last-child .td { padding-bottom: @printer-padding-base; }

    .printer-ticket   .tfoot  .sup .td {
        padding: @printer-padding-base 0;
        border-top: 1px dashed gray;
    }
    .sup.p--0 .td { padding-bottom: 0; }

    .printer-ticket .titulos{
        border-top: 1px solid gray;
        border-bottom: 1px dashed gray;
    }

    .printer-ticket   .title { font-size: 1.5em; padding: @printer-padding-base*1.5 0; }
    .printer-ticket   .top .td { padding-top: @printer-padding-base; }

    .printer-ticket   .last .td { padding-bottom: @printer-padding-base; }

</style>

<div class='table printer-ticket'>
    
    <div class='tbody'>

        <?php if (!empty($pedido_array_bolo)): ?>

            <div class='sup ttu p--0'>
                <div  class='titulos'>
                    <b>Bolos B<?= $pedido_bolo_id ?></b>
                </div>
            </div>

            <?php
            $array_bolo = json_decode($pedido_array_bolo, true);

            foreach ($array_bolo as $key => $bolo):
                extract($bolo);
                if (!empty($categoria_bolo_id)):
                    $Read->ExeRead('categoria_bolos', "WHERE categoria_bolo_id =:categoria_id", "categoria_id={$categoria_bolo_id}");
                    extract($Read->getResult()[0]);
                    ?>
                    <div class='top'>
                        <div class='colspan'><b><?= $categoria_bolo_nome ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($categoria_bolo_preco_kg)):
                    ?>
                    <div class='tr'>
                        <div class='td'>R$<?= ($pedido_is_kit_festa == 1 ? $categoria_bolo_kit_festa : $categoria_bolo_preco_kg) ?></div>
                        <div class='td'>$<?= $pedido_bolo_peso ?>kg</div>
                        <div class='td'>R$<?= $pedido_bolo_valor ?></div>
                    </div>
                    <?php
                endif;


                if (!empty($recheio_especial)):
                    $recheio = '';
                    $preco = '';
                    foreach ($recheio_especial as $recheio_id):
//extract($comum);
                        $Read->ExeRead('recheios', "WHERE recheio_id =:recheio_id", "recheio_id={$recheio_id}");
                        extract($Read->getResult()[0]);
                        $recheio .= $recheio_nome . ", ";
                        $preco += $recheio_preco_kg;

                    endforeach;

                    $recheio = rtrim($recheio, ', ');
                    ?>
                    <div class='tr'>
                        <div class='colspan'><b>Recheios:</b><?= $recheio ?></div>
                    </div>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>

                    <?php
                endif;

                if (!empty($pedido_bolo_papel_arroz)):
                    ?>
                    <div class='tr'>
                        <div class='colspan'><b>Papel de arroz:</b><?= $pedido_bolo_papel_arroz ?></div>
                    </div>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>
                    <?php
                endif;

                if (!empty($pedido_bolo_cores)):
                    ?>
                    <div class='tr'>
                        <div class='colspan'><b>Cores:</b><?= $pedido_bolo_cores ?></div>
                    </div>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>
                    <?php
                endif;

                if (!empty($pedido_bolo_escrita)):
                    ?>
                    <div class='tr'>
                        <div class='colspan'><b>Escrita:</b><?= $pedido_bolo_escrita ?></div>
                    </div>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>
                    <?php
                endif;

                if (!empty($pedido_bolo_observacoes)):
                    ?>
                    <div class='tr'>
                        <div class='colspan'><b>Observações:</b><?= $pedido_bolo_observacoes ?></div>
                    </div>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'></div>
                        <div class='td'></div>
                    </div>
                    <?php
                endif;

            endforeach;
           ?>
            <pagebreak />
            <?php
        endif; //END BOLO
        ?>
       

        <?php if (!empty($pedido_array_torta)):
            ?>
            <div class = 'sup ttu p--0'>
                <div  class='titulos'>
                    <b>Tortas T<?= $pedido_torta_id ?></b>
                </div>
            </div>

            <?php
            $array_torta = json_decode($pedido_array_torta, true);

            foreach ($array_torta as $key => $torta):
                extract($torta);
                if (!empty($categoria_torta_id)):
                    $Read->ExeRead('categoria_tortas', "WHERE categoria_torta_id =:categoria_id", "categoria_id={$categoria_torta_id}");
                    extract($Read->getResult()[0]);
                    ?>
                    <div class='top'>
                        <div class='colspan'><b><?= $categoria_torta_nome ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($categoria_torta_preco_kg)):
                    ?>
                    <div class='tr'>
                        <div class='td'>R$<?= ($pedido_is_kit_festa == 1 ? $categoria_torta_kit_festa : $categoria_torta_preco_kg) ?></div>
                        <div class='td'><?= $pedido_torta_peso ?> kg</div>
                        <div class='td'>R$<?= $pedido_torta_valor ?></div>
                    </div>
                    <?php
                endif;

            endforeach;
            ?>
            <pagebreak />
            <?php
        endif; //END Torta
        ?>



        <?php
        if (!empty($pedido_array_salgado)):
            ?>
            <div class = 'sup ttu p--0'>
                <div  class='titulos'>
                    <b>Salgados S<?= $pedido_bolo_id ?></b>
                </div>
            </div>
            <?php
            $array_salgado = json_decode($pedido_array_salgado, true);

            foreach ($array_salgado as $key => $salgado):
                extract($salgado);
                if (!empty($salgado_id)):
                    $Read->ExeRead('salgados', "WHERE salgado_id =:salgado_id", "salgado_id={$salgado_id}");
                    extract($Read->getResult()[0]);
                    ?>
                    <div class='top'>
                        <div class='colspan'><b><?= $salgado_nome ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($salgado_preco)):
                    ?>
                    <div class='tr'>
                        <div class='td'>R$<?= ($pedido_is_kit_festa == 1 ? $salgado_kit_festa : $salgado_preco) ?></div>
                        <div class='td'><?= $pedido_salgado_qtd ?>Qtd.</div>
                        <div class='td'>R$<?= $pedido_salgado_valor ?></div>
                    </div>
                    <?php
                endif;

            endforeach;
            ?>
            <pagebreak />
            <?php
        endif; //END salgado
        ?>


        <?php if (!empty($pedido_array_docinho)): ?>

            <div class = 'sup ttu p--0'>
                <div  class='titulos'>
                    <b>Doces D<?= $pedido_docinho_id ?></b>
                </div>
            </div>

            <?php
            $array_docinho = json_decode($pedido_array_docinho, true);

            foreach ($array_docinho as $key => $docinho):
                extract($docinho);
                if (!empty($docinho_id)):
                    $Read->ExeRead('docinhos', "WHERE docinho_id =:docinho_id", "docinho_id={$docinho_id}");
                    extract($Read->getResult()[0]);
                    ?>
                    <div class='top'>
                        <div class='colspan'><b><?= $docinho_nome ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($docinho_preco)):
                    ?>
                    <div class='tr'>
                        <div class='td'>R$ <?= ($pedido_is_kit_festa == 1 ? $docinho_preco_kit_festa : $docinho_preco) ?></div>
                        <div class='td'><?= $pedido_docinho_qtd ?> Qtd.</div>
                        <div class='td'>R$<?= $pedido_docinho_valor ?></div>
                    </div>
                    <?php
                endif;

            endforeach;
            ?>
            <pagebreak />
            <?php
        endif; //END docinho
        ?>


        <?php if (!empty($pedido_array_refrigerante)): ?>

            <div class = 'sup ttu p--0'>
                <div  class='titulos'>
                    <b>Refrigerantes R<?= $pedido_refrigerante_id ?></b>
                </div>
            </div>

            <?php
            $array_refrigerante = json_decode($pedido_array_refrigerante, true);

            foreach ($array_refrigerante as $key => $refrigerante):
                extract($refrigerante);
                if (!empty($refrigerante_id)):
                    $Read->ExeRead('refrigerantes', "WHERE refrigerante_id =:refrigerante_id", "refrigerante_id={$refrigerante_id}");
                    extract($Read->getResult()[0]);
                    ?>
                    <div class='top'>
                        <div><b><?= $refrigerante_nome ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($refrigerante_preco)):
                    ?>
                    <div class='tr'>
                        <div class='td'>R$<?= ($pedido_is_kit_festa == 1 ? $refrigerante_preco_kit_festa : $refrigerante_preco ) ?></div>
                        <div class='td'><?= $pedido_refrigerante_qtd ?> Qtd.</div>
                        <div class='td'>R$<?= $pedido_refrigerante_valor ?></div>
                    </div>
                    <?php
                endif;

            endforeach;
            ?>
            <pagebreak />
            <?php
        endif; //END refrigerante

        if (!empty($pedido_outros_array)):
            ?>
            <div class = 'sup ttu p--0'>
                <div  class='titulos'>
                    <b>Outros O<?= $pedido_outros_id ?></b>
                </div>
            </div>

            <?php
            $array_outros = json_decode($pedido_outros_array, true);

            foreach ($array_outros as $key => $outros):
                extract($outros);
                if (!empty($pedido_outros)):
                    ?>
                    <div class='top'>
                        <div class='colspan'><b><?= $pedido_outros ?></b></div>
                    </div>

                    <?php
                endif;

                if (!empty($pedido_outros_valor_unidade)):
                    ?>
                    <div class='tr'>
                        <div class='td'></div>
                        <div class='td'><?= $pedido_outros_kg_qtd ?>Qtd.</div>
                        <div class='td'>R$<?= ($pedido_outros_kg_qtd * $pedido_outros_valor_unidade) ?></div>
                    </div>
                    <?php
                endif;

            endforeach;

        endif; //END outros
        ?>
    </div>

    <div class = 'sup ttu p--0'>
        <div>
            <b>Totais</b>
        </div>
    </div>

    <?php if ($pedido_is_kit_festa == 1): ?>

        <div class = 'ttu'>
            <div class='colspan'>Kit Festa</div>
            <div class = 'text-right'></div>
        </div>

    <?php endif; ?>


    <div class = 'ttu'>
        <div class='colspan'>Total</div>
        <div class = 'text-right'>R$ <?= number_format($pedido_total, 2, ',', '.') ?></div>
    </div>

    <?php if (!empty($pedido_entrada)): ?>
        <div class = 'ttu'>
            <div class='colspan'>Entrada</div>
            <div class = 'text-right'>R$<?= number_format($pedido_entrada, 2, ',', '.') ?></div>
        </div>


        <div class = 'ttu'>
            <div class='colspan'>Restante</div>
            <div class = 'text-right'>R$<?= number_format($pedido_restante, 2, ',', '.') ?></div>
        </div>

    <?php endif; ?>

    <div class = 'sup'>
        <div class='text-center'>
            <b>Pedido:</b>
        </div>
    </div>
    <div class='sup'>
        <div  class = 'text-center'>
            <?= BASE ?>
        </div>
    </div>
</div>


<?php
$html = ob_get_contents();
ob_end_clean();

require_once '../../vendor/autoload.php';

$PDF = new Mpdf\Mpdf(['mode' => '',
    'format' => [100,126],
    'default_font_size' => 0,
    'default_font' => '',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 20,
    'margin_bottom' => 20,
    'margin_header' => 5,
    'margin_footer' => 5,
    'orientation' => 'P',]);

$arquivo = 'producao-' . time() . '.pdf';

$PDF->SetHTMLHeader("<div class='thead'>

        <div class='tr'>
            <div class='ttu' class='colspan'>
                <b>Nº Pedido Geral:</b>
                P$num_pedido
            </div>
        </div>

    </div>
", '0', true);

$PDF->SetHTMLFooter("  $cliente_nome <br />
                 $cliente_telefone_1 ". (!empty($cliente_telefone_2) ? " $cliente_telefone_2" : ''));

$PDF->WriteHTML($html);
$PDF->Output($arquivo, 'I');



//require_once '../../vendor/autoload.php';
//
//$mpdf = new \Mpdf\Mpdf();
//$mpdf->WriteHTML('<h1>Hello world!</h1>');
//$mpdf->Output();
