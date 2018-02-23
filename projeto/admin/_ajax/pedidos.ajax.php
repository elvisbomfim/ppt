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

        $DADOS['cliente_nome_id'] = "<option value=\"$cliente_id\" data-select2-id=\"\">{$cliente_nome}</option>";
        $DADOS['pedido_data_criacao'] = date_format(new DateTime($pedido_data_criacao), "d/M/Y");
        $DADOS['pedido_data_retirada'] = date_format(new DateTime($pedido_data_retirada), "d/M/Y");

        $Read->ExeRead('categoria_bolos');
        $concatena_select_bolo = "";
        $concatena_select_bolo .= "<option disabled='' value=''>Selecione a categoria</option>";

        foreach ($Read->getResult() as $value):
            extract($value);
            $concatena_select_bolo .= "<option value='{$categoria_bolo_id}' " . ($categoria_bolo_id == $categoria_id ? 'selected=""' : '' ) . ">{$categoria_bolo_nome}</option>";
        endforeach;
        $Read->ExeRead('recheios', " WHERE recheio_status = 1 AND recheio_tipo = 1");


        if (!empty($pedido_array_recheio_especial)):
            $array = json_decode($pedido_array_recheio_especial, true);
            
            $i = 0;
            foreach ($array as $value) {
                
                $concatena_recheio_especial = "<option value = '' disabled = ''>Selecione a categoria</option>";
                foreach ($Read->getResult() as $tab_recheio) {
                    extract($tab_recheio);
                    $concatena_recheio_especial .= "<option value='{$recheio_id}' " . ($recheio_id == $value['recheio_id'] ? 'selected=""' : '' ) . ">{$recheio_nome} R$ " . number_format($recheio_preco_kg, 2, ',', '.') . "</option>";
                    
                }
                $DADOS['recheio_especial_' . $i] = $concatena_recheio_especial;
                $i++;
            }
        endif;



        $DADOS['categoria_bolo_id'] = $concatena_select_bolo;
        $DADOS['pedido_bolo_peso'] = $pedido_bolo_peso;
        $DADOS['pedido_bolo_valor'] = $pedido_bolo_valor;
        //$DADOS['pedido_bolo_massa'] = $pedido_bolo_massa;
        $DADOS['pedido_bolo_papel_arroz'] = $pedido_bolo_papel_arroz;
        $DADOS['pedido_bolo_cores'] = $pedido_bolo_cores;
        $DADOS['pedido_bolo_escrita'] = $pedido_bolo_escrita;
        $DADOS['pedido_bolo_observacoes'] = $pedido_bolo_observacoes;
        $DADOS['pedido_bolo_valor_total'] = $pedido_bolo_valor_total;
        $jSON["dados"] = $DADOS;
        $jSON["type"] = "atualizado";
        break;

    case 'calcular':


        $total_geral = 0.00;
        $total_geral_bolo = 0;
        $total_geral_torta = 0;
        $total_geral_salgado = 0;
        $total_geral_doce = 0;
        $total_geral_refrigerante = 0;



        // Bolo
        if (!empty($POST['categoria_bolo_id'])):
            $Read->ExeRead("categoria_bolos", "WHERE categoria_bolo_id =:id", "id={$POST['categoria_bolo_id']}");
            $total_bolo = 0;
            if ($Read->getResult()):

                $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['categoria_bolo_preco_kg'] * $POST['pedido_bolo_peso'] : $Read->getResult()[0]['categoria_bolo_kit_festa'] * $POST['pedido_bolo_peso'] );
                //$calculo = $Read->getResult()[0]['categoria_bolo_preco_kg'] * $pedido_bolo_peso;

                if (!empty($total_bolo)) :
                    $total_bolo = str_replace(',', '.', str_replace('.', '', $total_bolo));
                endif;

                if (!empty($POST['recheio_especial'])):
                    //print_r($POST['recheio_especial']);
                    $total_recheio = 0;
                    foreach ($POST['recheio_especial'] as $recheio):

                        if (!empty($recheio)):
                            //echo ($recheio);
                            $Read->ExeRead("recheios", "WHERE recheio_id =:id", "id={$recheio}");

                            $total_recheio += $Read->getResult()[0]['recheio_preco_kg'];
                        endif;

                    endforeach;

                endif;

                $total_bolo += $total_recheio;
                $total_bolo += $calculo;
                $total_geral_bolo = $total_bolo;

                $calculo = number_format($calculo, 2, ',', '.');
                $total_bolo = number_format($total_bolo, 2, ',', '.');


                //    print_r($bolo);
                $jSON["categoria"][] = ["name" => "pedido_bolo_valor", "valor_item" => "{$calculo}", "total_parcial" => "{$total_bolo}", "id_total_parcial" => "pedido_bolo_valor_total"]; //type = warning danger success        break;



            endif;

        endif;

        if (!empty($POST['tortas'])):

            //print_r($POST['tortas']);
            $total_torta = 0.00;
            if (!empty($total_torta)) {
                $total_torta = str_replace(',', '.', str_replace('.', '', $total_torta));
            }

            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);
                //  if(!empty()):
                //  endif;

                $Read->ExeRead("categoria_tortas", "WHERE categoria_torta_id =:id", "id=$categoria_torta_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['categoria_torta_preco_kg'] * $pedido_torta_peso : $Read->getResult()[0]['categoria_torta_kit_festa'] * $pedido_torta_peso );
                    //$calculo = $Read->getResult()[0]['categoria_torta_preco_kg'] * $pedido_torta_peso;

                    if (!empty($total_torta)) {
                        $total_torta = str_replace(',', '.', str_replace('.', '', $total_torta));
                    }



                    $total_torta += $calculo;
                    $total_geral_torta = $total_torta;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_torta = number_format($total_torta, 2, ',', '.');


                    //    print_r($torta);
                    $jSON["categoria"][] = ["name" => "tortas[{$key}][pedido_torta_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_torta}", "id_total_parcial" => "pedido_torta_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;


        endif;


        if (!empty($POST['salgados'])):

            $total_salgado = 0.00;

            foreach ($POST['salgados'] as $key => $salgado):
                extract($salgado);
                //  if(!empty()):
                //  endif;

                $Read->ExeRead("salgados", "WHERE salgado_id =:id", "id=$salgado_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['salgado_preco'] * $pedido_salgado_qtd : $Read->getResult()[0]['salgado_kit_festa'] * $pedido_salgado_qtd );
                    //$calculo = $Read->getResult()[0]['salgado_preco'] * $pedido_salgado_qtd;

                    if (!empty($total_salgado)) {
                        $total_salgado = str_replace(',', '.', str_replace('.', '', $total_salgado));
                    }

                    $total_salgado += $calculo;
                    $total_geral_salgado = $total_salgado;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_salgado = number_format($total_salgado, 2, ',', '.');


                    //    print_r($salgado);
                    $jSON["categoria"][] = ["name" => "salgados[{$key}][pedido_salgado_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_salgado}", "id_total_parcial" => "pedido_salgado_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;
        endif;


        if (!empty($POST['doces'])):

            $total_doce = 0.00;

            foreach ($POST['doces'] as $key => $doce):
                extract($doce);
                //  if(!empty()):
                //  endif;

                $Read->ExeRead("docinhos", "WHERE docinho_id =:id", "id=$docinho_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['docinho_preco'] * $pedido_docinho_qtd : $Read->getResult()[0]['docinho_preco_kit_festa'] * $pedido_docinho_qtd );
                    //$calculo = $Read->getResult()[0]['docinho_preco'] * $pedido_docinho_qtd;

                    if (!empty($total_doce)) {
                        $total_doce = str_replace(',', '.', str_replace('.', '', $total_doce));
                    }

                    $total_doce += $calculo;
                    $total_geral_doce = $total_doce;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_doce = number_format($total_doce, 2, ',', '.');


                    //    print_r($doce);
                    $jSON["categoria"][] = ["name" => "doces[{$key}][pedido_docinho_valor_unidade]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_doce}", "id_total_parcial" => "pedido_doce_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;



        endif;

        if (!empty($POST['refrigerantes'])):

            $total_refrigerante = 0.00;

            foreach ($POST['refrigerantes'] as $key => $refrigerante):
                extract($refrigerante);
                //  if(!empty()):
                //  endif;

                $Read->ExeRead("refrigerantes", "WHERE refrigerante_id =:id", "id=$refrigerante_id");

                if ($Read->getResult()):

                    $calculo = (empty($POST['kit_festa']) ? $Read->getResult()[0]['refrigerante_preco'] * $pedido_refrigerante_qtd : $Read->getResult()[0]['refrigerante_preco_kit_festa'] * $pedido_refrigerante_qtd );
                    //$calculo = $Read->getResult()[0]['refrigerante_preco'] * $pedido_refrigerante_qtd;

                    if (!empty($total_refrigerante)) {
                        $total_refrigerante = str_replace(',', '.', str_replace('.', '', $total_refrigerante));
                    }

                    $total_refrigerante += $calculo;
                    $total_geral_refrigerante = $total_refrigerante;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_refrigerante = number_format($total_refrigerante, 2, ',', '.');


                    //    print_r($refrigerante);
                    $jSON["categoria"][] = ["name" => "refrigerantes[{$key}][pedido_refrigerante_valor_unidade]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_refrigerante}", "id_total_parcial" => "pedido_refrigerante_valor_total"]; //type = warning danger success        break;



                endif;

            endforeach;

        endif;

        $total_geral = $total_geral_bolo + $total_geral_torta + $total_geral_salgado + $total_geral_doce + $total_geral_refrigerante;

        $total_geral = number_format($total_geral, 2, ',', '.');
        $jSON['total_geral_pedido'] = $total_geral;





        break;

    case 'create':

        if (empty($POST['id'])):

        else:

        endif;
        if (empty($POST['cliente_id'])):
            $jSON['erro'] = "Preencha o nome do cliente!";
            break;
        endif;

        if (empty($POST['pedido_total'])):
            $jSON['pedido_form_reset'] = true;
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "O formulário do pedido está em branco, preencha pelo menos uma categoria!", "URL" => "", "Target" => "_blank", "type" => "warning"]; //type = warning danger success
            break;
        endif;

        $pedidos['cliente_id'] = $POST['cliente_id'];
        $pedidos['pedido_data_criacao'] = $POST['pedido_data_criacao'];
        $pedidos['pedido_data_retirada'] = $POST['pedido_data_retirada'];
        $POST['pedido_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_total']));
        $pedidos['pedido_total'] = $POST['pedido_total'];
        $pedidos['pedido_status'] = 0;

        if (!empty($POST['kit_festa'])):
            $pedidos['pedido_is_kit_festa'] = 1;
        else:
            $pedidos['pedido_is_kit_festa'] = 0;
        endif;

        $Create->ExeCreate('pedidos', $pedidos);
        $ultimo_pedido_id = $Create->getResult();


        if (!empty($POST['categoria_bolo_id'])):


            $pedido_bolo['pedido_id'] = $ultimo_pedido_id;
            $pedido_bolo['categoria_id'] = $POST['categoria_bolo_id'];
            $pedido_bolo['pedido_bolo_peso'] = $POST['pedido_bolo_peso'];

            $POST['pedido_bolo_valor'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_bolo_valor']));

            $pedido_bolo['pedido_bolo_valor'] = $POST['pedido_bolo_valor'];
            $pedido_bolo['pedido_bolo_massa'] = $POST['pedido_bolo_massa'];
            if (!empty($POST['recheio_especial'])):
                $array_recheio_especial = '';
                foreach ($POST['recheio_especial'] as $recheio):
                    if (!empty($recheio)):
                        $array_recheio_especial[] = ["recheio_id" => $recheio];
                    endif;
                endforeach;

            endif;
//
            if (!empty($POST['recheio_comum'])):
                $array_recheio_comum = "";
                foreach ($POST['recheio_comum'] as $recheio):
                    if (!empty($recheio)):
                        $array_recheio_comum[] = ["recheio_id" => $recheio];
                    endif;
                endforeach;
            endif;

            if (!empty($array_recheio_especial)):
                $pedido_bolo['pedido_array_recheio_especial'] = json_encode($array_recheio_especial);
            endif;
            if (!empty($array_recheio_comum)):
                $pedido_bolo['pedido_array_recheio_comum'] = json_encode($array_recheio_comum);
            endif;

            $pedido_bolo['pedido_bolo_papel_arroz'] = $POST['pedido_bolo_papel_arroz'];
            $pedido_bolo['pedido_bolo_cores'] = $POST['pedido_bolo_cores'];
            $pedido_bolo['pedido_bolo_escrita'] = $POST['pedido_bolo_escrita'];
            $pedido_bolo['pedido_bolo_observacoes'] = $POST['pedido_bolo_observacoes'];
            $pedido_bolo['pedido_bolo_status'] = 0;
            
            $POST['pedido_bolo_valor_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_bolo_valor_total']));
            
            $pedido_bolo['pedido_bolo_valor_total'] = $POST['pedido_bolo_valor_total'];

            $Create = clone $Create;
            $Create->ExeCreate('pedidos_bolo', $pedido_bolo);

        endif;

        if (!empty($POST['tortas'])):
            $pedido_torta['pedido_id'] = $ultimo_pedido_id;
            $array_torta = "";
            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);
                if (!empty($categoria_torta_id)):
                    $array_torta[] = ["pedido_torta_peso" => $pedido_torta_peso, "pedido_torta_valor" => $pedido_torta_valor, "categoria_torta_id" => $categoria_torta_id];

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

        if (!empty($POST['salgados'])):
            $pedido_salgado['pedido_id'] = $ultimo_pedido_id;
            $array_salgado = "";
            foreach ($POST['salgados'] as $key => $salgado):
                extract($salgado);
                if (!empty($salgado_id)):
                    $array_salgado[] = ["pedido_salgado_qtd" => $pedido_salgado_qtd, "pedido_salgado_valor" => $pedido_salgado_valor, "salgado_id" => $salgado_id];

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

        if (!empty($POST['doces'])):
            $pedido_doce['pedido_id'] = $ultimo_pedido_id;
            $array_doce = "";
            foreach ($POST['doces'] as $key => $doce):
                extract($doce);
                if (!empty($docinho_id)):
                    $array_doce[] = ["pedido_docinho_qtd" => $pedido_docinho_qtd, "pedido_docinho_valor" => $pedido_docinho_valor_unidade, "docinho_id" => $docinho_id];

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

        if (!empty($POST['refrigerantes'])):

            $pedido_refrigerante['pedido_id'] = $ultimo_pedido_id;
            $array_refrigerante = "";
            foreach ($POST['refrigerantes'] as $key => $refrigerante):
                extract($refrigerante);
                if (!empty($refrigerante_id)):
                    $array_refrigerante[] = ["pedido_refrigerante_qtd" => $pedido_refrigerante_qtd, "pedido_refrigerante_valor" => $pedido_refrigerante_valor_unidade, "refrigerante_id" => $refrigerante_id];
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

        break;

    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $POST['pedido_total'] = str_replace(',', '.', str_replace('.', '', $POST['pedido_total']));

        $Update->ExeUpdate('pedidos', $POST, 'WHERE pedido_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Doce $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success

        $Read->ExeRead('pedidos', "WHERE pedido_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        $pedido_total = number_format($pedido_total, 2, ',', '.');

        if ($TYPE == "criado"):
            $jSON["result"] = " <tr id='{$pedido_id}'>
                                    <td>{$pedido_total}</td>
                                    <td> " . ($pedido_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='pedidos' data-callback_action='manager' data-id='{$pedido_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='pedidos' data-callback_action='delete' data-id='{$pedido_id}' data-name='{$pedido_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = "     <td>{$pedido_total}</td>
                                    <td> " . ($pedido_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='pedidos' data-callback_action='manager' data-id='{$pedido_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='pedidos' data-callback_action='delete' data-id='{$pedido_id}' data-name='{$pedido_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':

        $Delete->ExeDelete('pedidos', 'WHERE pedido_id =:id', "id={$POST["id"]}");
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
