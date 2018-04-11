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

$jSON["tabela"] = "pedidosTable";

$jSON["idmodal"] = "pedidosModal";

function CupomFiscal($param_id) {

    $Read = new Read;

    $Read->FullRead("SELECT *, p.pedido_id as num_pedido FROM pedidos p "
            . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
            . "LEFT JOIN pedidos_bolo pb ON pb.pedido_id = p.pedido_id "
            . "LEFT JOIN pedidos_docinho pd ON pd.pedido_id = p.pedido_id "
            . "LEFT JOIN pedidos_refrigerante pr ON pr.pedido_id = p.pedido_id "
            . "LEFT JOIN pedidos_salgado ps ON ps.pedido_id = p.pedido_id "
            . "LEFT JOIN pedidos_torta pt ON pt.pedido_id = p.pedido_id "
            . "WHERE p.pedido_id =:id ", "id={$param_id}");

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
			<th colspan='3'>" . $datetime->format('Y-m-d H:i') . "</th>
		</tr>
		<tr>
			<th colspan='3'>
				" . $cliente_nome . " <br />
				$cliente_telefone_1 " . (!empty($cliente_telefone_2) ? "/ $cliente_telefone_2" : '') . "  
			</th>
		</tr>
		<tr>
			<th class='ttu' colspan='3'>
				<b>Cupom não fiscal</b> <br>
                                P" . $num_pedido . "
			</th>
		</tr>

</thead>
        
	<tbody>";

    if (!empty($pedido_array_bolo)):

        $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
<td colspan = '3' class='titulos'>
<b>Bolos B" . $pedido_bolo_id . "</b>
</td>
</tr>";

        $array_bolo = json_decode($pedido_array_bolo, true);

        foreach ($array_bolo as $key => $bolo):
            extract($bolo);
            if (!empty($categoria_bolo_id)):
                $Read->ExeRead('categoria_bolos', "WHERE categoria_bolo_id =:categoria_id", "categoria_id={$categoria_bolo_id}");
                extract($Read->getResult()[0]);

                $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$categoria_bolo_nome</b></td>
		</tr>";

            endif;

            if (!empty($categoria_bolo_preco_kg)):
                $jSON['resultcupom'] .= "<tr>
			<td>R$ " . ($pedido_is_kit_festa == 1 ? $categoria_bolo_kit_festa : $categoria_bolo_preco_kg) . "</td>
			<td>$pedido_bolo_peso kg</td>
			<td>R$$pedido_bolo_valor</td>
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

        endforeach;

    endif; //END BOLO

    if (!empty($pedido_array_torta)):

        $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
<td colspan = '3' class='titulos'>
<b>Tortas T" . $pedido_torta_id . "</b>
</td>
</tr>";

        $array_torta = json_decode($pedido_array_torta, true);

        foreach ($array_torta as $key => $torta):
            extract($torta);
            if (!empty($categoria_torta_id)):
                $Read->ExeRead('categoria_tortas', "WHERE categoria_torta_id =:categoria_id", "categoria_id={$categoria_torta_id}");
                extract($Read->getResult()[0]);

                $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$categoria_torta_nome</b></td>
		</tr>";

            endif;

            if (!empty($categoria_torta_preco_kg)):
                $jSON['resultcupom'] .= "<tr>
			<td>R$ " . ($pedido_is_kit_festa == 1 ? $categoria_torta_kit_festa : $categoria_torta_preco_kg) . "</td>
			<td>$pedido_torta_peso kg</td>
			<td>R$$pedido_torta_valor</td>
		</tr>";
            endif;

        endforeach;

    endif; //END Torta

    if (!empty($pedido_array_salgado)):

        $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
<td colspan = '3' class='titulos'>
<b>Salgados S" . $pedido_bolo_id . "</b>
</td>
</tr>";

        $array_salgado = json_decode($pedido_array_salgado, true);

        foreach ($array_salgado as $key => $salgado):
            extract($salgado);
            if (!empty($salgado_id)):
                $Read->ExeRead('salgados', "WHERE salgado_id =:salgado_id", "salgado_id={$salgado_id}");
                extract($Read->getResult()[0]);

                $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$salgado_nome</b></td>
		</tr>";

            endif;

            if (!empty($salgado_preco)):
                $jSON['resultcupom'] .= "<tr>
			<td>R$" . ($pedido_is_kit_festa == 1 ? $salgado_kit_festa : $salgado_preco) . "</td>
			<td>$pedido_salgado_qtd Qtd.</td>
			<td>R$$pedido_salgado_valor</td>
		</tr>";
            endif;

        endforeach;

    endif; //END salgado

    if (!empty($pedido_array_docinho)):

        $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
<td colspan = '3' class='titulos'>
<b>Doces D" . $pedido_docinho_id . "</b>
</td>
</tr>";

        $array_docinho = json_decode($pedido_array_docinho, true);

        foreach ($array_docinho as $key => $docinho):
            extract($docinho);
            if (!empty($docinho_id)):
                $Read->ExeRead('docinhos', "WHERE docinho_id =:docinho_id", "docinho_id={$docinho_id}");
                extract($Read->getResult()[0]);

                $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$docinho_nome</b></td>
		</tr>";

            endif;

            if (!empty($docinho_preco)):
                $jSON['resultcupom'] .= "<tr>
			<td>R$ " . ($pedido_is_kit_festa == 1 ? $docinho_preco_kit_festa : $docinho_preco) . "</td>
			<td>$pedido_docinho_qtd Qtd.</td>
			<td>R$$pedido_docinho_valor</td>
		</tr>";
            endif;

        endforeach;

    endif; //END docinho

    if (!empty($pedido_array_refrigerante)):

        $jSON['resultcupom'] .= "<tr class = 'sup ttu p--0'>
<td colspan = '3' class='titulos'>
<b>Refrigerantes R" . $pedido_refrigerante_id . "</b>
</td>
</tr>";

        $array_refrigerante = json_decode($pedido_array_refrigerante, true);

        foreach ($array_refrigerante as $key => $refrigerante):
            extract($refrigerante);
            if (!empty($refrigerante_id)):
                $Read->ExeRead('refrigerantes', "WHERE refrigerante_id =:refrigerante_id", "refrigerante_id={$refrigerante_id}");
                extract($Read->getResult()[0]);

                $jSON['resultcupom'] .= "  <tr class='top'>
			<td colspan='3'><b>$refrigerante_nome</b></td>
		</tr>";

            endif;

            if (!empty($refrigerante_preco)):
                $jSON['resultcupom'] .= "<tr>
			<td>R$" . ($pedido_is_kit_festa == 1 ? $refrigerante_preco_kit_festa : $refrigerante_preco ) . "</td>
			<td>$pedido_refrigerante_qtd Qtd.</td>
			<td>R$$pedido_refrigerante_valor</td>
		</tr>";
            endif;

        endforeach;

    endif; //END refrigerante

    $jSON['resultcupom'] .= ("</tbody>
<tfoot>
<tr class = 'sup ttu p--0'>
<td colspan = '3'>
<b>Totais</b>
</td>
</tr>
");
    if ($pedido_is_kit_festa == 1):

        $jSON['resultcupom'] .= ("<tr class = 'ttu'>
<td colspan = '2'>Kit Festa</td>
<td align = 'right'></td>
</tr>");

    endif;

    $jSON['resultcupom'] .= ("
<tr class = 'ttu'>
<td colspan = '2'>Total</td>
<td align = 'right'>R$" . number_format($pedido_total, 2, ',', '.') . "</td>
</tr>

<tr class = 'sup'>
<td colspan = '3' align = 'center'>
<b>Pedido:</b>
</td>
</tr>
<tr class = 'sup'>
<td colspan = '3' align = 'center'>
" . BASE . "
</td>
</tr>
</tfoot>
</table>");

    return($jSON['resultcupom']);
}

function verDetalhes (){
    
}

switch ($Action):

    case 'manager':

        $Read->FullRead("SELECT * FROM pedidos p "
                . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                . "LEFT JOIN pedidos_bolo pb ON pb.pedido_id = p.pedido_id "
                . "LEFT JOIN pedidos_docinho pd ON pd.pedido_id = p.pedido_id "
                . "LEFT JOIN pedidos_refrigerante pr ON pr.pedido_id = p.pedido_id "
                . "LEFT JOIN pedidos_salgado ps ON ps.pedido_id = p.pedido_id "
                . "LEFT JOIN pedidos_torta pt ON pt.pedido_id = p.pedido_id "
                . "WHERE p.pedido_id =:id ", "id={$POST['id']}");


        extract($Read->getResult()[0]);




        $jSON["manager"] = true;
        $jSON["id"] = $POST['id'];
        $DADOS = [];

        $date_criacao = date('Y-m-d H:i', strtotime($pedido_data_criacao));
        $datetimecriacao = new DateTime($date_criacao);

        $date_retirada = date('Y-m-d H:i', strtotime($pedido_data_retirada));
        $datetimeretirada = new DateTime($date_retirada);

        $DADOS['pedido_total'] = number_format($pedido_total, 2, ',', '.');
        $DADOS['pedido_is_kit_festa'] = $pedido_is_kit_festa;

        $DADOS['cliente_nome_id'] = "<option value=\"$cliente_id\" data-select2-id=\"" . time() . "\">{$cliente_nome}</option>";
//$datetime->format('Y-m-d\TH:i:s');
        $DADOS['pedido_data_criacao'] = $datetimecriacao->format('Y-m-d\TH:i');
        $DADOS['pedido_data_retirada'] = $datetimeretirada->format('Y-m-d\TH:i');

// $DADOS['pedido_data_criacao'] = date('Y-m-d H:i:s', strtotime($pedido_data_criacao));
// $DADOS['pedido_data_retirada'] = date('Y-m-d H:i:s', strtotime($pedido_data_retirada));

        if (!empty($pedido_array_bolo)):

            $total_bolo = number_format($pedido_bolo_valor_total, 2, ',', '.');
            ;

            $array_bolo = json_decode($pedido_array_bolo, true);

            $i = 0;


            foreach ($array_bolo as $key => $value):
//print_r($value);

                $Read->ExeRead('categoria_bolos');

                $concatena_select_bolo = "<option disabled='' value=''>Selecione a categoria</option>";

                foreach ($Read->getResult() as $tab_bolo):
                    extract($tab_bolo);
                    $concatena_select_bolo .= "<option value='{$categoria_bolo_id}' " . ($categoria_bolo_id == $value['categoria_bolo_id'] ? 'selected=""' : '' ) . ">{$categoria_bolo_nome}</option>";
                endforeach;
                $DADOS['bolos'][$key]["bolos[{$key}][categoria_bolo_id]"] = $concatena_select_bolo;

//$jSON["categoria"][] = ["name" => "bolos[{$key}][pedido_bolo_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_bolo}", "id_total_parcial" => "pedido_bolo_valor_total"]; //type = warning danger success        break;


                $Read->ExeRead('recheios', " WHERE recheio_status = 1 AND recheio_tipo = 1");
                if (!empty($value['recheio_especial'])):

                    $array_recheio_especial = '';
                    foreach ($value['recheio_especial'] as $recheio):

                        $concatena_recheio_especial = "<option value = '' disabled = ''>Selecione a categoria</option>";
                        foreach ($Read->getResult() as $tab_recheio) :
                            extract($tab_recheio);
                            $concatena_recheio_especial .= "<option value='{$recheio_id}' " . ($recheio_id == $recheio ? 'selected=""' : '' ) . ">{$recheio_nome} R$ " . number_format($recheio_preco_kg, 2, ',', '.') . "</option>";
                        endforeach;
                        $DADOS['bolos'][$key]["bolos[{$key}][recheio_especial]"][] = $concatena_recheio_especial;

                    endforeach;

                endif;
//
// $Read->ExeRead('recheios', " WHERE recheio_status = 1 AND recheio_tipo = 0");


                if (!empty($value['recheio_comum'])):
                    $concatena_recheio_comum = "";
                    $array_recheio_comum = "";
                    foreach ($value['recheio_comum'] as $recheio):
// foreach ($array_recheio_comum_dividido[1] as $tab_recheio) :
// extract($tab_recheio);
//$array_recheio_comum[] = $recheio;
                        $DADOS['bolos'][$key]["bolos[{$key}][recheio_comum]"][] = $recheio;
//  endforeach;

                    endforeach;
                endif;


                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_peso]"] = $value['pedido_bolo_peso'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_valor]"] = $value['pedido_bolo_valor'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_massa]"] = $value['pedido_bolo_massa'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_papel_arroz]"] = $value['pedido_bolo_papel_arroz'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_escrita]"] = $value['pedido_bolo_escrita'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_cores]"] = $value['pedido_bolo_cores'];
                $DADOS['bolos'][$key]["bolos[{$key}][pedido_bolo_observacoes]"] = $value['pedido_bolo_observacoes'];

            endforeach;
            $DADOS['pedido_bolo_valor_total'] = $total_bolo;
        endif;

        if (!empty($pedido_array_torta)):

            $total_torta = $pedido_torta_valor_total;

            $array_torta = json_decode($pedido_array_torta, true);

            $i = 0;


            foreach ($array_torta as $key => $value):
//print_r($value);

                $Read->ExeRead('categoria_tortas');

                $concatena_select_torta = "<option disabled='' value=''>Selecione a categoria</option>";

                foreach ($Read->getResult() as $tab_torta):
                    extract($tab_torta);
                    $concatena_select_torta .= "<option value='{$categoria_torta_id}' " . ($categoria_torta_id == $value['categoria_torta_id'] ? 'selected=""' : '' ) . ">{$categoria_torta_nome}</option>";
                endforeach;
                $DADOS['tortas'][$key]["tortas[{$key}][categoria_torta_id]"] = $concatena_select_torta;

                $DADOS['tortas'][$key]["tortas[{$key}][pedido_torta_peso]"] = $value['pedido_torta_peso'];
                $DADOS['tortas'][$key]["tortas[{$key}][pedido_torta_valor]"] = $value['pedido_torta_valor'];

            endforeach;
            $DADOS['pedido_torta_valor_total'] = number_format($total_torta, 2, ',', '.');
        endif;

        if (!empty($pedido_array_salgado)):

            $total_salgado = $pedido_salgado_valor_total;

            $array_salgado = json_decode($pedido_array_salgado, true);

            $i = 0;


            foreach ($array_salgado as $key => $value):
//print_r($value);

                $Read->ExeRead('salgados');

                $concatena_select_salgado = "<option disabled='' value=''>Selecione a categoria</option>";

                foreach ($Read->getResult() as $tab_salgado):
                    extract($tab_salgado);
                    $concatena_select_salgado .= "<option value='{$salgado_id}' " . ($salgado_id == $value['salgado_id'] ? 'selected=""' : '' ) . ">{$salgado_nome}</option>";
                endforeach;
                $DADOS['salgados'][$key]["salgados[{$key}][salgado_id]"] = $concatena_select_salgado;

                $DADOS['salgados'][$key]["salgados[{$key}][pedido_salgado_qtd]"] = $value['pedido_salgado_qtd'];
                $DADOS['salgados'][$key]["salgados[{$key}][pedido_salgado_valor]"] = $value['pedido_salgado_valor'];

            endforeach;
            $DADOS['pedido_salgado_valor_total'] = number_format($total_salgado, 2, ',', '.');
        endif;

        if (!empty($pedido_array_refrigerante)):

            $total_refrigerante = $pedido_refrigerante_valor_total;

            $array_refrigerante = json_decode($pedido_array_refrigerante, true);

            $i = 0;


            foreach ($array_refrigerante as $key => $value):
//print_r($value);

                $total_refrigerante = $pedido_refrigerante_valor_total;

                $Read->ExeRead('refrigerantes');

                $concatena_select_refrigerante = "<option disabled='' value=''>Selecione a categoria</option>";

                foreach ($Read->getResult() as $tab_refrigerante):
                    extract($tab_refrigerante);
                    $concatena_select_refrigerante .= "<option value='{$refrigerante_id}' " . ($refrigerante_id == $value['refrigerante_id'] ? 'selected=""' : '' ) . ">{$refrigerante_nome}</option>";
                endforeach;
                $DADOS['refrigerantes'][$key]["refrigerantes[{$key}][refrigerante_id]"] = $concatena_select_refrigerante;

                $DADOS['refrigerantes'][$key]["refrigerantes[{$key}][pedido_refrigerante_qtd]"] = $value['pedido_refrigerante_qtd'];
                $DADOS['refrigerantes'][$key]["refrigerantes[{$key}][pedido_refrigerante_valor_unidade]"] = $value['pedido_refrigerante_valor'];

            endforeach;
            $DADOS['pedido_refrigerante_valor_total'] = number_format($total_refrigerante, 2, ',', '.');
        endif;

        if (!empty($pedido_array_docinho)):

            $total_docinho = $pedido_docinho_valor_total;

            $array_docinho = json_decode($pedido_array_docinho, true);

            $i = 0;


            foreach ($array_docinho as $key => $value):
//print_r($value);

                $Read->ExeRead('docinhos');

                $concatena_select_docinho = "<option disabled='' value=''>Selecione a categoria</option>";

                foreach ($Read->getResult() as $tab_docinho):
                    extract($tab_docinho);
                    $concatena_select_docinho .= "<option value='{$docinho_id}' " . ($docinho_id == $value['docinho_id'] ? 'selected=""' : '' ) . ">{$docinho_nome}</option>";
                endforeach;
                $DADOS['doces'][$key]["doces[{$key}][docinho_id]"] = $concatena_select_docinho;

                $DADOS['doces'][$key]["doces[{$key}][pedido_docinho_qtd]"] = $value['pedido_docinho_qtd'];
                $DADOS['doces'][$key]["doces[{$key}][pedido_docinho_valor_unidade]"] = $value['pedido_docinho_valor'];

            endforeach;
            $DADOS['pedido_docinho_valor_total'] = number_format($total_docinho, 2, ',', '.');
        endif;



        $jSON["dadospedidos"] = $DADOS;
        $jSON["manager"] = true;
        $jSON["type"] = "atualizado";
        break;

    case 'calcular':


        $total_geral = 0.00;
        $total_geral_bolo = 0;
        $total_geral_torta = 0;
        $total_geral_salgado = 0;
        $total_geral_doce = 0;
        $total_geral_refrigerante = 0;


        if (!empty($POST['bolos'])):

//print_r($POST['bolos']);
            $total_bolo = 0.00;
            if (!empty($total_bolo)) {
                $total_bolo = str_replace(',', '.', str_replace('.', '', $total_torta));
            }

            foreach ($POST['bolos'] as $key => $bolo):
                extract($bolo);

//  if(!empty()):
//  endif;

                $Read->ExeRead("categoria_bolos", "WHERE categoria_bolo_id =:id", "id=$categoria_bolo_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['categoria_bolo_preco_kg'] * $pedido_bolo_peso : $Read->getResult()[0]['categoria_bolo_kit_festa'] * $pedido_bolo_peso );
//$calculo = $Read->getResult()[0]['categoria_torta_preco_kg'] * $pedido_torta_peso;

                    if (!empty($total_bolo)) {
                        $total_bolo = str_replace(',', '.', str_replace('.', '', $total_bolo));
                    }

                    if (!empty($recheio_especial)):
//print_r($POST['recheio_especial']);
                        $total_recheio = 0;
                        foreach ($recheio_especial as $recheio):

                            if (!empty($recheio)):
//echo ($recheio);
                                $Read->ExeRead("recheios", "WHERE recheio_id =:id", "id={$recheio}");

                                $total_recheio += $Read->getResult()[0]['recheio_preco_kg'];
                            endif;

                        endforeach;
                        $total_bolo += $total_recheio;
                    endif;


                    $total_bolo += $calculo;
                    $total_geral_bolo = $total_bolo;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_bolo = number_format($total_bolo, 2, ',', '.');


//    print_r($torta);
                    $jSON["categoria"][] = ["name" => "bolos[{$key}][pedido_bolo_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_bolo}", "id_total_parcial" => "pedido_bolo_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;


        endif;


        if (!empty($POST['tortas'])):


            $total_torta = 0.00;
            if (!empty($total_torta)) {
                $total_torta = str_replace(',', '.', str_replace('.', '', $total_torta));
            }

            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);


                $Read->ExeRead("categoria_tortas", "WHERE categoria_torta_id =:id", "id=$categoria_torta_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['categoria_torta_preco_kg'] * $pedido_torta_peso : $Read->getResult()[0]['categoria_torta_kit_festa'] * $pedido_torta_peso );

                    if (!empty($total_torta)) {
                        $total_torta = str_replace(',', '.', str_replace('.', '', $total_torta));
                    }



                    $total_torta += $calculo;
                    $total_geral_torta = $total_torta;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_torta = number_format($total_torta, 2, ',', '.');



                    $jSON["categoria"][] = ["name" => "tortas[{$key}][pedido_torta_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_torta}", "id_total_parcial" => "pedido_torta_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;


        endif;


        if (!empty($POST['salgados'])):

            $total_salgado = 0.00;

            foreach ($POST['salgados'] as $key => $salgado):
                extract($salgado);


                $Read->ExeRead("salgados", "WHERE salgado_id =:id", "id=$salgado_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['salgado_preco'] * $pedido_salgado_qtd : $Read->getResult()[0]['salgado_kit_festa'] * $pedido_salgado_qtd );

                    if (!empty($total_salgado)) {
                        $total_salgado = str_replace(',', '.', str_replace('.', '', $total_salgado));
                    }

                    $total_salgado += $calculo;
                    $total_geral_salgado = $total_salgado;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_salgado = number_format($total_salgado, 2, ',', '.');



                    $jSON["categoria"][] = ["name" => "salgados[{$key}][pedido_salgado_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_salgado}", "id_total_parcial" => "pedido_salgado_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;
        endif;


        if (!empty($POST['doces'])):

            $total_doce = 0.00;

            foreach ($POST['doces'] as $key => $doce):
                extract($doce);


                $Read->ExeRead("docinhos", "WHERE docinho_id =:id", "id=$docinho_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['docinho_preco'] * $pedido_docinho_qtd : $Read->getResult()[0]['docinho_preco_kit_festa'] * $pedido_docinho_qtd );

                    if (!empty($total_doce)) {
                        $total_doce = str_replace(',', '.', str_replace('.', '', $total_doce));
                    }

                    $total_doce += $calculo;
                    $total_geral_doce = $total_doce;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_doce = number_format($total_doce, 2, ',', '.');



                    $jSON["categoria"][] = ["name" => "doces[{$key}][pedido_docinho_valor_unidade]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_doce}", "id_total_parcial" => "pedido_doce_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;



        endif;

        if (!empty($POST['refrigerantes'])):

            $total_refrigerante = 0.00;

            foreach ($POST['refrigerantes'] as $key => $refrigerante):
                extract($refrigerante);


                $Read->ExeRead("refrigerantes", "WHERE refrigerante_id =:id", "id=$refrigerante_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['refrigerante_preco'] * $pedido_refrigerante_qtd : $Read->getResult()[0]['refrigerante_preco_kit_festa'] * $pedido_refrigerante_qtd );

                    if (!empty($total_refrigerante)) {
                        $total_refrigerante = str_replace(',', '.', str_replace('.', '', $total_refrigerante));
                    }

                    $total_refrigerante += $calculo;
                    $total_geral_refrigerante = $total_refrigerante;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_refrigerante = number_format($total_refrigerante, 2, ',', '.');



                    $jSON["categoria"][] = ["name" => "refrigerantes[{$key}][pedido_refrigerante_valor_unidade]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_refrigerante}", "id_total_parcial" => "pedido_refrigerante_valor_total"]; //type = warning danger success        break;



                endif;

            endforeach;

        endif;

        $total_geral = $total_geral_bolo + $total_geral_torta + $total_geral_salgado + $total_geral_doce + $total_geral_refrigerante;

        $total_geral = number_format($total_geral, 2, ',', '.');
        $jSON['total_geral_pedido'] = $total_geral;





        break;

    case 'create':

        if (empty($POST['pedido_data_retirada'])):
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Preencha a data de retirada!", "URL" => "", "Target" => "_blank", "type" => "warning"]; //type = warning danger success
            $jSON['resetInputCalcular'] = true;
            break;
        else:

        endif;
        if (empty($POST['cliente_id'])):
            $jSON['erro'] = "Preencha o nome do cliente!";
            $jSON['resetInputCalcular'] = true;
            break;
        endif;

        if (empty($POST['pedido_total'])):
            $jSON['pedido_form_reset'] = true;
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "O formulário do pedido está em branco, preencha pelo menos uma categoria!", "URL" => "", "Target" => "_blank", "type" => "warning"]; //type = warning danger success
            $jSON['resetInputCalcular'] = true;
            break;
        endif;

        $pedidos['cliente_id'] = $POST['cliente_id'];
        $pedidos['pedido_data_criacao'] = $POST['pedido_data_criacao'];
        $pedidos['pedido_data_retirada'] = $POST['pedido_data_retirada'];
        $POST['pedido_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_total']));
        $pedidos['pedido_total'] = $POST['pedido_total'];
        $pedidos['pedido_status'] = 1;

        if (!empty($POST['kit_festa'])):
            $pedidos['pedido_is_kit_festa'] = 1;
        else:
            $pedidos['pedido_is_kit_festa'] = 0;
        endif;

        $Create->ExeCreate('pedidos', $pedidos);
        $ultimo_pedido_id = $Create->getResult();

        $read = new Read;

        $read->FullRead("SELECT * FROM pedidos p "
                . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                . "WHERE p.pedido_id = {$ultimo_pedido_id}");

        extract($read->getResult()[0]);

        $jSON["resultPedidoCreate"] = " <tr id='{$pedido_id}'>
                            <td>P{$pedido_id}</td>
                            <td>{$cliente_nome}</td>
                            <td>{$pedido_data_criacao}</td>
                            <td>{$pedido_data_retirada}</td>
                            <td>{$pedido_total}</td>
                                <td>{$pedido_status}</td>
                            <td>
<button class='btn btn-warning j_action get_action_name' title='Editar Pedido' data-action-name='update' data-callback='pedidos' data-callback_action='manager' data-id='{$pedido_id}'><i class='fa fa-edit'></i></button> 
                     <button class='btn btn-primary j_action' title='Duplicar Pedido' data-callback='pedidos' data-callback_action='duplicar' data-id='{$pedido_id}'><i class='fa fa-copy'></i> </button>
                     <button class='btn btn-danger' title='Cancelar Pedido'  data-callback='pedidos' data-callback_action='cancelar' data-id='{$pedido_id}' data-name='' data-toggle='modal' data-target='#confirmar-cancelar'><i class='fa fa-ban'></i></button>       
                     
                             </td>
                        </tr>";



        if (!empty($POST['bolos'][0]['categoria_bolo_id'])):
            $pedido_bolo['pedido_id'] = $ultimo_pedido_id;
            $array_bolo = "";
            foreach ($POST['bolos'] as $key => $bolo):

                extract($bolo);

                if (!empty($categoria_bolo_id)):
                    $array_recheio_especial = "";
                    if (!empty($recheio_especial)):

                        foreach ($recheio_especial as $recheio):
                            if (!empty($recheio)):

                                $array_recheio_especial[] = $recheio;
                            endif;
                        endforeach;

                    endif;
                    $array_recheio_comum = "";
                    if (!empty($recheio_comum)):

                        foreach ($recheio_comum as $recheio):
                            if (!empty($recheio)):
                                $array_recheio_comum[] = $recheio;
                            endif;
                        endforeach;
                    endif;

                    $array_bolo[] = [
                        "pedido_bolo_peso" => $pedido_bolo_peso,
                        "pedido_bolo_valor" => $pedido_bolo_valor,
                        "categoria_bolo_id" => $categoria_bolo_id,
                        "pedido_bolo_massa" => $pedido_bolo_massa,
                        "recheio_comum" => $array_recheio_comum,
                        "recheio_especial" => $array_recheio_especial,
                        "pedido_bolo_papel_arroz" => $pedido_bolo_papel_arroz,
                        "pedido_bolo_cores" => $pedido_bolo_cores,
                        "pedido_bolo_escrita" => $pedido_bolo_escrita,
                        "pedido_bolo_observacoes" => $pedido_bolo_observacoes,
                        "bolo_status" => 2
                    ];





                endif;
            endforeach;


            if (!empty($array_bolo)):
                $pedido_bolo['pedido_array_bolo'] = json_encode($array_bolo);
                $_POST['pedido_bolo_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_bolo_valor_total']));
                $pedido_bolo['pedido_bolo_valor_total'] = $_POST['pedido_bolo_valor_total'];
                $pedido_bolo['pedido_bolo_status'] = 2;
                $Create = clone $Create;
                $Create->ExeCreate('pedidos_bolo', $pedido_bolo);
            endif;




        endif;

        if (!empty($POST['tortas'][0]['categoria_torta_id'])):
            $pedido_torta['pedido_id'] = $ultimo_pedido_id;
            $array_torta = "";
            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);
                if (!empty($categoria_torta_id)):
                    $array_torta[] = [
                        "pedido_torta_peso" => $pedido_torta_peso,
                        "pedido_torta_valor" => $pedido_torta_valor,
                        "categoria_torta_id" => $categoria_torta_id,
                        "torta_status" => 2,
                    ];

                endif;
            endforeach;
            if (!empty($array_torta)):
                $pedido_torta['pedido_array_torta'] = json_encode($array_torta);
                $_POST['pedido_torta_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_torta_valor_total']));
                $pedido_torta['pedido_torta_valor_total'] = $_POST['pedido_torta_valor_total'];

                $pedido_torta['pedido_torta_status'] = 1;

                $Create = clone $Create;
                $Create->ExeCreate('pedidos_torta', $pedido_torta);
            endif;



        endif;

        if (!empty($POST['salgados'][0]['salgado_id'])):
            $pedido_salgado['pedido_id'] = $ultimo_pedido_id;
            $array_salgado = "";
            foreach ($POST['salgados'] as $key => $salgado):
                extract($salgado);
                if (!empty($salgado_id)):
                    $array_salgado[] = ["pedido_salgado_qtd" => $pedido_salgado_qtd, "pedido_salgado_valor" => $pedido_salgado_valor, "salgado_id" => $salgado_id, "salgado_status" => 2];

                endif;
            endforeach;

            if (!empty($array_salgado)):
                $pedido_salgado['pedido_array_salgado'] = json_encode($array_salgado);
                $_POST['pedido_salgado_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_salgado_valor_total']));
                $pedido_salgado['pedido_salgado_valor_total'] = $_POST['pedido_salgado_valor_total'];

                $pedido_salgado['pedido_salgado_status'] = 1;

                $Create = clone $Create;
                $Create->ExeCreate('pedidos_salgado', $pedido_salgado);
            endif;



        endif;

        if (!empty($POST['doces'][0]['docinho_id'])):
            $pedido_doce['pedido_id'] = $ultimo_pedido_id;
            $array_doce = "";
            foreach ($POST['doces'] as $key => $doce):
                extract($doce);
                if (!empty($docinho_id)):
                    $array_doce[] = ["pedido_docinho_qtd" => $pedido_docinho_qtd, "pedido_docinho_valor" => $pedido_docinho_valor_unidade, "docinho_id" => $docinho_id, "docinho_status" => 2];

                endif;
            endforeach;
            if (!empty($array_doce)):
                $pedido_doce['pedido_array_docinho'] = json_encode($array_doce);
                $_POST['pedido_docinho_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_docinho_valor_total']));
                $pedido_doce['pedido_docinho_valor_total'] = $_POST['pedido_docinho_valor_total'];

                $pedido_doce['pedido_docinho_status'] = 1;

                $Create = clone $Create;
                $Create->ExeCreate('pedidos_docinho', $pedido_doce);
            endif;



        endif;

        if (!empty($POST['refrigerantes'][0]['refrigerante_id'])):

            $pedido_refrigerante['pedido_id'] = $ultimo_pedido_id;
            $array_refrigerante = "";
            foreach ($POST['refrigerantes'] as $key => $refrigerante):
                extract($refrigerante);
                if (!empty($refrigerante_id)):
                    $array_refrigerante[] = ["pedido_refrigerante_qtd" => $pedido_refrigerante_qtd, "pedido_refrigerante_valor" => $pedido_refrigerante_valor_unidade, "refrigerante_id" => $refrigerante_id, "refrigerante_status" => 2];
                endif;

            endforeach;
            if (!empty($array_refrigerante)):
                $pedido_refrigerante['pedido_array_refrigerante'] = json_encode($array_refrigerante);
                $_POST['pedido_refrigerante_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_refrigerante_valor_total']));
                $pedido_refrigerante['pedido_refrigerante_valor_total'] = $_POST['pedido_refrigerante_valor_total'];

                $pedido_refrigerante['pedido_refrigerante_status'] = 1;

                $Create = clone $Create;
                $Create->ExeCreate('pedidos_refrigerante', $pedido_refrigerante);
            endif;



        endif;

//        $jSON["manager"] = true;
//        $jSON["id"] = $Create->getResult();
//        $jSON["type"] = "criado";

        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Pedido criado com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success

        $jSON['resultcupom'] = CupomFiscal($ultimo_pedido_id);

        $jSON['idmodalcupom'] = "cupomfiscalModal";



        break;

    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        if (empty($POST['pedido_data_retirada'])):
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Preencha a data de retirada!", "URL" => "", "Target" => "_blank", "type" => "warning"]; //type = warning danger success
            $jSON['resetInputCalcular'] = true;
            break;
        else:

        endif;
        if (empty($POST['cliente_id'])):
            $jSON['erro'] = "Preencha o nome do cliente!";
            $jSON['resetInputCalcular'] = true;
            break;
        endif;

        if (empty($POST['pedido_total'])):
            $jSON['pedido_form_reset'] = true;
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "O formulário do pedido está em branco, preencha pelo menos uma categoria!", "URL" => "", "Target" => "_blank", "type" => "warning"]; //type = warning danger success
            $jSON['resetInputCalcular'] = true;
            break;
        endif;

        $pedidos['cliente_id'] = $POST['cliente_id'];
        $pedidos['pedido_data_criacao'] = $POST['pedido_data_criacao'];
        $pedidos['pedido_data_retirada'] = $POST['pedido_data_retirada'];
        $POST['pedido_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_total']));
        $pedidos['pedido_total'] = $POST['pedido_total'];
//$pedidos['pedido_status'] = 1;

        if (!empty($POST['kit_festa'])):
            $pedidos['pedido_is_kit_festa'] = 1;
        else:
            $pedidos['pedido_is_kit_festa'] = 0;
        endif;

        $Update->ExeUpdate('pedidos', $pedidos, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");


        $read = new Read;

        $read->FullRead("SELECT * FROM pedidos p "
                . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                . "WHERE p.pedido_id = {$ID}");

        extract($read->getResult()[0]);

        $jSON["resultPedidoUpdate"] = "<td>{$ID}</td>
          <td>{$cliente_nome}</td>
          <td>{$pedido_data_criacao}</td>
          <td>{$pedido_data_retirada}</td>
          <td>" . number_format($pedido_total, 2, ',', '.') . "</td>
              <td>{$pedido_total}</td>
          <td>
          <button class='btn btn-warning j_action get_action_name' title='Editar Pedido' data-action-name='update' data-callback='pedidos' data-callback_action='manager' data-id='{$pedido_id}'><i class='fa fa-edit'></i></button> 
                     <button class='btn btn-primary j_action' title='Duplicar Pedido' data-callback='pedidos' data-callback_action='duplicar' data-id='{$pedido_id}'><i class='fa fa-copy'></i> </button>
                     <button class='btn btn-danger' title='Cancelar Pedido'  data-callback='pedidos' data-callback_action='cancelar' data-id='{$pedido_id}' data-name='' data-toggle='modal' data-target='#confirmar-cancelar'><i class='fa fa-ban'></i></button>       
          </td>";

        $jSON["id"] = $ID;

        if (!empty($POST['bolos'][0]['categoria_bolo_id'])):

            $array_bolo = "";
            foreach ($POST['bolos'] as $key => $bolo):

                extract($bolo);

                if (!empty($categoria_bolo_id)):
                    $array_recheio_especial = "";
                    if (!empty($recheio_especial)):

                        foreach ($recheio_especial as $recheio):
                            if (!empty($recheio)):

                                $array_recheio_especial[] = $recheio;
                            endif;
                        endforeach;

                    endif;
                    $array_recheio_comum = "";
                    if (!empty($recheio_comum)):

                        foreach ($recheio_comum as $recheio):
                            if (!empty($recheio)):
                                $array_recheio_comum[] = $recheio;
                            endif;
                        endforeach;
                    endif;

                    $array_bolo[] = [
                        "pedido_bolo_peso" => $pedido_bolo_peso,
                        "pedido_bolo_valor" => $pedido_bolo_valor,
                        "categoria_bolo_id" => $categoria_bolo_id,
                        "pedido_bolo_massa" => $pedido_bolo_massa,
                        "recheio_comum" => $array_recheio_comum,
                        "recheio_especial" => $array_recheio_especial,
                        "pedido_bolo_papel_arroz" => $pedido_bolo_papel_arroz,
                        "pedido_bolo_cores" => $pedido_bolo_cores,
                        "pedido_bolo_escrita" => $pedido_bolo_escrita,
                        "pedido_bolo_observacoes" => $pedido_bolo_observacoes,
                        "bolo_status" => 2
                    ];


                    $pedido_bolo['pedido_array_bolo'] = json_encode($array_bolo);
                    $_POST['pedido_bolo_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_bolo_valor_total']));
                    $pedido_bolo['pedido_bolo_valor_total'] = $_POST['pedido_bolo_valor_total'];


                endif;
            endforeach;


            $Update->ExeUpdate('pedidos_bolo', $pedido_bolo, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");



        endif;

        if (!empty($POST['tortas'][0]['categoria_torta_id'])):

            $array_torta = "";
            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);
                if (!empty($categoria_torta_id)):
                    $array_torta[] = ["pedido_torta_peso" => $pedido_torta_peso, "pedido_torta_valor" => $pedido_torta_valor, "categoria_torta_id" => $categoria_torta_id, 'torta_status' => 2];

                endif;
            endforeach;
            if (!empty($array_torta)):
                $pedido_torta['pedido_array_torta'] = json_encode($array_torta);
                $_POST['pedido_torta_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_torta_valor_total']));
                $pedido_torta['pedido_torta_valor_total'] = $_POST['pedido_torta_valor_total'];

                $pedido_torta['pedido_torta_status'] = 1;

                $Update->ExeUpdate('pedidos_torta', $pedido_torta, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");


            endif;



        endif;

        if (!empty($POST['salgados'][0]['salgado_id'])):

            $array_salgado = "";
            foreach ($POST['salgados'] as $key => $salgado):
                extract($salgado);
                if (!empty($salgado_id)):
                    $array_salgado[] = ["pedido_salgado_qtd" => $pedido_salgado_qtd, "pedido_salgado_valor" => $pedido_salgado_valor, "salgado_id" => $salgado_id, 'salgado_status' => 2];

                endif;
            endforeach;

            if (!empty($array_salgado)):
                $pedido_salgado['pedido_array_salgado'] = json_encode($array_salgado);
                $_POST['pedido_salgado_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_salgado_valor_total']));
                $pedido_salgado['pedido_salgado_valor_total'] = $_POST['pedido_salgado_valor_total'];

                $pedido_salgado['pedido_salgado_status'] = 1;

                $Update->ExeUpdate('pedido_salgado', $pedido_salgado, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");


            endif;



        endif;

        if (!empty($POST['doces'][0]['docinho_id'])):

            $array_doce = "";
            foreach ($POST['doces'] as $key => $doce):
                extract($doce);
                if (!empty($docinho_id)):
                    $array_doce[] = ["pedido_docinho_qtd" => $pedido_docinho_qtd, "pedido_docinho_valor" => $pedido_docinho_valor_unidade, "docinho_id" => $docinho_id, 'docinho_status' => 2];

                endif;
            endforeach;
            if (!empty($array_doce)):
                $pedido_doce['pedido_array_docinho'] = json_encode($array_doce);
                $_POST['pedido_docinho_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_docinho_valor_total']));
                $pedido_doce['pedido_docinho_valor_total'] = $_POST['pedido_docinho_valor_total'];

                $pedido_doce['pedido_docinho_status'] = 1;

                $Update->ExeUpdate('pedidos_docinho', $pedido_doce, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");

            endif;



        endif;

        if (!empty($POST['refrigerantes'][0]['refrigerante_id'])):


            $array_refrigerante = "";
            foreach ($POST['refrigerantes'] as $key => $refrigerante):
                extract($refrigerante);
                if (!empty($refrigerante_id)):
                    $array_refrigerante[] = ["pedido_refrigerante_qtd" => $pedido_refrigerante_qtd, "pedido_refrigerante_valor" => $pedido_refrigerante_valor_unidade, "refrigerante_id" => $refrigerante_id, 'refrigerante_status' => 2];
                endif;

            endforeach;
            if (!empty($array_refrigerante)):
                $pedido_refrigerante['pedido_array_refrigerante'] = json_encode($array_refrigerante);
                $_POST['pedido_refrigerante_valor_total'] = str_replace(',', '.', str_replace('.', '', $_POST['pedido_refrigerante_valor_total']));
                $pedido_refrigerante['pedido_refrigerante_valor_total'] = $_POST['pedido_refrigerante_valor_total'];

                $pedido_refrigerante['pedido_refrigerante_status'] = 1;

                $Update->ExeUpdate('pedidos_refrigerante', $pedido_refrigerante, "WHERE pedido_id =:pedido_id", "pedido_id={$ID}");


            endif;



        endif;

        $POST['pedido_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_total']));

        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Pedido atualizado com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success


        $jSON['resultcupom'] = CupomFiscal($ID);

        $jSON['idmodalcupom'] = "cupomfiscalModal";

        break;

    case 'duplicar':

        $jSON["result"] = null;
        $ID = $POST['id'];
//$TYPE = $POST['type'];
        unset($POST['id']);

        $Read->ExeRead('pedidos', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_id']);
            $array['pedido_data_criacao'] = date("Y-m-d H:i");
            $array['pedido_data_retirada'] = date("Y-m-d H:i");
            $Create->ExeCreate('pedidos', $array);
            $ultimo_pedido_id = $Create->getResult();
        endif;

        $Read->ExeRead('pedidos_bolo', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_bolo_id']);
            unset($array['pedido_id']);
            $array['pedido_id'] = $ultimo_pedido_id;
            $Create->ExeCreate('pedidos_bolo', $array);
        endif;

        $Read->ExeRead('pedidos_docinho', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_docinho_id']);
            unset($array['pedido_id']);
            $array['pedido_id'] = $ultimo_pedido_id;
            $Create->ExeCreate('pedidos_docinho', $array);
        endif;

        $Read->ExeRead('pedidos_refrigerante', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_refrigerante_id']);
            unset($array['pedido_id']);
            $array['pedido_id'] = $ultimo_pedido_id;
            $Create->ExeCreate('pedidos_refrigerante', $array);
        endif;

        $Read->ExeRead('pedidos_torta', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_torta_id']);
            unset($array['pedido_id']);
            $array['pedido_id'] = $ultimo_pedido_id;
            $Create->ExeCreate('pedidos_torta', $array);
        endif;

        $Read->ExeRead('pedidos_salgado', "WHERE pedido_id =:id", "id={$ID}");

        if ($Read->getResult()):
            $array = $Read->getResult()[0];
            unset($array['pedido_salgado_id']);
            unset($array['pedido_id']);
            $array['pedido_id'] = $ultimo_pedido_id;
            $Create->ExeCreate('pedidos_salgado', $array);
        endif;

        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Pedido duplicado com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success

        $Read->FullRead("SELECT * FROM pedidos p "
                . "INNER JOIN clientes c ON c.cliente_id = p.cliente_id "
                . "WHERE p.pedido_id =:id ", "id={$ultimo_pedido_id}");


        extract($Read->getResult()[0]);


        $jSON["resultPedidoCreate"]['pedido_id'] = "P" . $pedido_id;
        $jSON["resultPedidoCreate"]['cliente_nome'] = $cliente_nome;
        $jSON["resultPedidoCreate"]['pedido_data_criacao'] = date("d/m/Y H:i", strtotime($pedido_data_criacao)) ;
        $jSON["resultPedidoCreate"]['pedido_data_retirada'] = date("d/m/Y H:i", strtotime($pedido_data_retirada));
        $jSON["resultPedidoCreate"]['pedido_total'] = number_format($pedido_total, 2, ',', '.');
        $jSON["resultPedidoCreate"]['pedido_status'] = $pedido_status;
        $jSON["resultPedidoCreate"]['botoes'] = "<button class = 'btn btn-warning j_action get_action_name' title = 'Editar Pedido' data-action-name = 'update' data-callback = 'pedidos' data-callback_action = 'manager' data-id = '{$pedido_id}'><i class = 'fa fa-edit'></i></button>
<button class = 'btn btn-primary j_action' title = 'Duplicar Pedido' data-callback = 'pedidos' data-callback_action = 'duplicar' data-id = '{$pedido_id}'><i class = 'fa fa-copy'></i> </button>
<button class = 'btn btn-danger' title = 'Cancelar Pedido' data-callback = 'pedidos' data-callback_action = 'cancelar' data-id = '{$pedido_id}' data-name = '' data-toggle = 'modal' data-target = '#confirmar-cancelar'><i class = 'fa fa-ban'></i></button>";

        //$jSON['redirect'] = BASE . 'admin/painel.php?exe=pedidos';

        break;


    case 'delete':

        $Delete->ExeDelete('pedidos', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
        $Delete->ExeDelete('pedidos_bolo', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
        $Delete->ExeDelete('pedidos_torta', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
        $Delete->ExeDelete('pedidos_docinho', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
        $Delete->ExeDelete('pedidos_salgado', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
        $Delete->ExeDelete('pedidos_refrigerante', 'WHERE pedido_id =:id', "id = {$POST["id"]}");
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
