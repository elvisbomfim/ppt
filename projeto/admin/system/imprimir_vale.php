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
    <div class='thead'>
        <div class='tr'>
            <div class='title' class='colspan'>Principe da Torta</div>
        </div>
        <div class='tr'>
            <div><?= $datetime->format('Y-m-d H:i') ?></div>
        </div>
        <div class='tr'>
            <div class='colspan'>
                <?= $cliente_nome ?><br />
                <?= $cliente_telefone_1 ?> <?= !empty($cliente_telefone_2) ? " $cliente_telefone_2" : '' ?>
            </div>
        </div>
        <div class='tr'>
            <div class='ttu' class='colspan'>
                <b>Cupom não fiscal</b> <br>
                P<?= $num_pedido ?>
            </div>
        </div>

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
    'format' => 'A4',
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


$PDF->WriteHTML($html);
$PDF->Output($arquivo, 'I');



//require_once '../../vendor/autoload.php';
//
//$mpdf = new \Mpdf\Mpdf();
//$mpdf->WriteHTML('<h1>Hello world!</h1>');
//$mpdf->Output();
