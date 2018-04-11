<?php

require_once '../../_app/Config.inc.php';
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//$setPost = array_map('strip_tags', $getPost);
//$POST = array_map('trim', $setPost);

$Action = $POST['callback_action'];
$Page = $POST['callback'];
unset($POST['callback_action']);
unset($POST['callback']);

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;
$jSON = null;
$jSON["result"] = null;

$jSON["tabela"] = "pedidosTable";

$jSON["idmodal"] = "pedidosModal";

function verDetalhes($dados) {
    $Read = new Read();
    $array = explode(",", $dados);


    $tabela = $array[0];
    $coluna = $array[1];
    $pedido_x_id = $array[2];
    $array_name = $array[3];
    $index_array = $array[4];
    $opcoes = $array[5];

    $Read->FullRead("SELECT * FROM pedidos p "
            . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
            . "LEFT JOIN $tabela tx ON tx.pedido_id = p.pedido_id "
            . "WHERE tx.$coluna =:id ", "id={$pedido_x_id}");

    //print_r($Read->getResult()[0]);
    extract($Read->getResult()[0]);


    $date_now = date('Y-m-d H:i');
    $datetime = new DateTime($date_now);

// echo '<pre>';
// var_dump($pedido_array_bolo);
// echo '</pre>';

    $jSON['resultcupom'] = "<table class='printer-ticket'>
 	<thead>
		<tr>
			<th class='title' colspan='3'>Principe da Torta</th>
		</tr>
		<tr>
			<th colspan='3'></th>
		</tr>
		<tr>
			<th colspan='3'>
				" . $cliente_nome . " <br />
				$cliente_telefone_1 " . (!empty($cliente_telefone_2) ? "/ $cliente_telefone_2" : '') . "  
			</th>
		</tr>
		<tr>
			<th class='ttu' colspan='3'>
				<b>Número do pedido geral.</b> <br>
                                P" . $pedido_id . "
			</th>
		</tr>

</thead>
        
	<tbody>";

    switch ($opcoes):

        case 'bolo':


            if (!empty($pedido_array_bolo)):

                $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
                    <td colspan = '3' class='titulos'>
                    <b>Bolos B" . $pedido_bolo_id . "</b></td>
                    </tr>";

                $array_bolo = json_decode($pedido_array_bolo, true);

                //print_r($array_bolo[$index_array]);

                extract($array_bolo[$index_array]);

                $Read->ExeRead('categoria_bolos', "WHERE categoria_bolo_id =:categoria_id", "categoria_id={$categoria_bolo_id}");
                extract($Read->getResult()[0]);


                if (!empty($categoria_bolo_id)):
                    $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$categoria_bolo_nome</b></td>
		</tr>";
                endif;

                if (!empty($categoria_bolo_preco_kg)):
                    $jSON['resultcupom'] .= "<tr>
			<td></td>
			<td>$pedido_bolo_peso kg</td>
			<td></td>
		</tr>";
                endif;

                if (!empty($recheio_comum)):

                    $recheio = '';
                    foreach ($recheio_comum as $recheio_id):
//extract($comum);
                        $Read->ExeRead('recheios', "WHERE recheio_id =:recheio_id AND recheio_tipo=0", "recheio_id={$recheio_id}");
                        extract($Read->getResult()[0]);
                        $recheio .= $recheio_nome . ", ";
                    endforeach;

                    $recheio = rtrim($recheio, ', ');

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Recheio Comum:</b> $recheio</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>";


                endif;

                if (!empty($recheio_especial)):
                    $recheio = '';
                    $preco = '';
                    foreach ($recheio_especial as $recheio_id):
//extract($comum);
                        $Read->ExeRead('recheios', "WHERE recheio_id =:recheio_id AND recheio_tipo=1", "recheio_id={$recheio_id}");
                        extract($Read->getResult()[0]);
                        $recheio .= $recheio_nome . ", ";
                        $preco += $recheio_preco_kg;

                    endforeach;

                    $recheio = rtrim($recheio, ', ');

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Recheio Especial:</b> $recheio</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>R$" . number_format($preco, 2, ',', '.') . "</td>
		</tr>";

                endif;

                if (!empty($pedido_bolo_papel_arroz)):

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Papel de arroz:</b> $pedido_bolo_papel_arroz</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
                endif;

                if (!empty($pedido_bolo_cores)):

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Cores:</b> $pedido_bolo_cores</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
                endif;

                if (!empty($pedido_bolo_escrita)):

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Escrita:</b> $pedido_bolo_escrita</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
                endif;

                if (!empty($pedido_bolo_observacoes)):

                    $jSON['resultcupom'] .= "  <tr>
			<td colspan='3'><b>Observações:</b> $pedido_bolo_observacoes</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
		</tr>";
                endif;


            endif;

            break;

        case 'torta':

            if (!empty($pedido_array_torta)):

                $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
                    <td colspan = '3' class='titulos'>
                    <b>Torta T" . $pedido_torta_id . "</b></td>
                    </tr>";

                $array_torta = json_decode($pedido_array_torta, true);

                //print_r($array_torta[$index_array]);

                extract($array_torta[$index_array]);

                $Read->ExeRead('categoria_tortas', "WHERE categoria_torta_id =:categoria_id", "categoria_id={$categoria_torta_id}");
                extract($Read->getResult()[0]);


                if (!empty($categoria_torta_id)):
                    $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$categoria_torta_nome</b></td>
		</tr>";
                endif;

                if (!empty($categoria_torta_preco_kg)):
                    $jSON['resultcupom'] .= "<tr>
			<td></td>
			<td>$pedido_torta_peso kg</td>
			<td></td>
		</tr>";
                endif;

            endif;

            break;

        case 'salgado':

            if (!empty($pedido_array_salgado)):

                $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
                    <td colspan = '3' class='titulos'>
                    <b>Salgado S" . $pedido_salgado_id . "</b></td>
                    </tr>";

                $array_salgado = json_decode($pedido_array_salgado, true);

                //print_r($array_salgado[$index_array]);

                extract($array_salgado[$index_array]);

                $Read->ExeRead('salgados', "WHERE salgado_id =:categoria_id", "categoria_id={$salgado_id}");
                extract($Read->getResult()[0]);


                if (!empty($salgado_id)):
                    $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$salgado_nome</b></td>
		</tr>";
                endif;

                if (!empty($pedido_salgado_qtd)):
                    $jSON['resultcupom'] .= "<tr>
			<td></td>
			<td>$pedido_salgado_qtd Qtd</td>
			<td></td>
		</tr>";
                endif;

            endif;

            break;

        case 'doce':

            if (!empty($pedido_array_docinho)):

                $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
                    <td colspan = '3' class='titulos'>
                    <b>Doces D" . $pedido_docinho_id . "</b></td>
                    </tr>";

                $array_docinho = json_decode($pedido_array_docinho, true);

                //print_r($array_docinho[$index_array]);

                extract($array_docinho[$index_array]);

                $Read->ExeRead('docinhos', "WHERE docinho_id =:categoria_id", "categoria_id={$docinho_id}");
                extract($Read->getResult()[0]);


                if (!empty($docinho_id)):
                    $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$docinho_nome</b></td>
		</tr>";
                endif;

                if (!empty($pedido_docinho_qtd)):
                    $jSON['resultcupom'] .= "<tr>
			<td></td>
			<td>$pedido_docinho_qtd Qtd</td>
			<td></td>
		</tr>";
                endif;

            endif;

            break;

        default :

            break;
    endswitch;

    $jSON['resultcupom'] .= "</tbody><tfoot>";




    $jSON['resultcupom'] .= "
</tfoot>
</table>";

    return($jSON['resultcupom']);
}

switch ($Action):

    case 'update-bolo':

        $ID = $POST['id'];
        $Key = $POST['key'];
        unset($POST['id'], $POST['key']);
        $Read->ExeRead("pedidos_bolo", "WHERE pedido_bolo_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array_bolo = json_decode($Read->getResult()[0]['pedido_array_bolo'], true);

            $array_bolo[$Key]['bolo_status'] = $POST['value'];

            $array_bolo = json_encode($array_bolo);

            $Dados = [
                'pedido_array_bolo' => $array_bolo
            ];
            $Update->ExeUpdate("pedidos_bolo", $Dados, "WHERE pedido_bolo_id =:id", "id={$ID}");
        endif;

        break;

    case 'update-torta':

        $ID = $POST['id'];
        $Key = $POST['key'];
        unset($POST['id'], $POST['key']);
        $Read->ExeRead("pedidos_torta", "WHERE pedido_torta_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array_torta = json_decode($Read->getResult()[0]['pedido_array_torta'], true);

            $array_torta[$Key]['torta_status'] = $POST['value'];

            $array_torta = json_encode($array_torta);

            $Dados = [
                'pedido_array_torta' => $array_torta
            ];
            $Update->ExeUpdate("pedidos_torta", $Dados, "WHERE pedido_torta_id =:id", "id={$ID}");
        endif;

        break;

    case 'update-salgado':

        $ID = $POST['id'];
        $Key = $POST['key'];
        unset($POST['id'], $POST['key']);
        $Read->ExeRead("pedidos_salgado", "WHERE pedido_salgado_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array_salgado = json_decode($Read->getResult()[0]['pedido_array_salgado'], true);

            $array_salgado[$Key]['salgado_status'] = $POST['value'];

            $array_salgado = json_encode($array_salgado);

            $Dados = [
                'pedido_array_salgado' => $array_salgado
            ];
            $Update->ExeUpdate("pedidos_salgado", $Dados, "WHERE pedido_salgado_id =:id", "id={$ID}");
        endif;

        break;

    case 'update-docinho':

        $ID = $POST['id'];
        $Key = $POST['key'];
        unset($POST['id'], $POST['key']);
        $Read->ExeRead("pedidos_docinho", "WHERE pedido_docinho_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array_docinho = json_decode($Read->getResult()[0]['pedido_array_docinho'], true);

            $array_docinho[$Key]['docinho_status'] = $POST['value'];

            $array_docinho = json_encode($array_docinho);

            $Dados = [
                'pedido_array_docinho' => $array_docinho
            ];
            $Update->ExeUpdate("pedidos_docinho", $Dados, "WHERE pedido_docinho_id =:id", "id={$ID}");
        endif;

        break;

    case 'detalhes':

        $jSON['resultcupom'] = verDetalhes($POST['dados']);

        $jSON['idmodalcupom'] = "cupomfiscalModal";

        break;

    case 'refresh':

        $contar = 0;

        switch ($POST['pedido_nome']):
            case 'bolo' :
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

                            $contar += count($array_bolo);

                        endif;
                    endforeach;
                endif;
                break;

            case 'torta' :
$Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
                                    . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                                    . "LEFT JOIN pedidos_torta pb ON pb.pedido_id = p.pedido_id "
                                    . "WHERE p.pedido_status != 6");
                            if (!empty($Read->getResult())):
                                foreach ($Read->getResult() as $pedido):
                                    extract($pedido);

                                    if (!empty($pedido_array_torta)):
                                        $total_torta = number_format($pedido_torta_valor_total, 2, ',', '.');
                                        $array_torta = json_decode($pedido_array_torta, true);
                                         $contar += count($array_torta);
                                    endif;
                                endforeach;
                            endif;
                break;
            case 'doce' :
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
 $contar += count($array_docinho);
                                       

                                       
                                    endif;
                                endforeach;
                            endif;
                break;
            case 'salgado' :
                
                $Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
                                    . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                                    . "LEFT JOIN pedidos_salgado pb ON pb.pedido_id = p.pedido_id "
                                    . "WHERE p.pedido_status != 6");
                            if (!empty($Read->getResult())):

                                foreach ($Read->getResult() as $pedido):

                                    extract($pedido);

                                    if (!empty($pedido_array_salgado)):

                                        $total_salgado = number_format($pedido_salgado_valor_total, 2, ',', '.');

                                        $array_salgado = json_decode($pedido_array_salgado, true);

                                        $contar += count($array_salgado);
                                       
                                    endif;
                                endforeach;
                            endif;

                break;

        endswitch;


        if($contar != $POST['contador']):            
            $jSON['redirect'] = BASE."admin/painel.php?exe=index&pedidos=".$POST['pedido_nome'];
        endif;
        

        break;


    case 'delete':


        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Doce apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Doce apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;


echo json_encode($jSON);
