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

    //CREATE
//    case 'manager':
//
//
//
//        $Read->ExeRead('pedidos', "WHERE pedido_id =:id", "id={$POST['doces']['pedido_id'][]}");
//
//        print_r($POST['doces']['pedido_id'][]);
//
//
//        $jSON['tot_pedido'] = $Read->getResult()[0]['pedido_preco'];

    case 'manager':




        if (!empty($POST['tortas'])):

            //print_r($POST['tortas']);
            $total_torta = 0.00;

            foreach ($POST['tortas'] as $key => $torta):
                extract($torta);
                //  if(!empty()):
                //  endif;

                $Read->ExeRead("categoria_tortas", "WHERE categoria_torta_id =:id", "id=$categoria_torta_id");

                if ($Read->getResult()):

                    $calculo = $Read->getResult()[0]['categoria_torta_preco_kg'] * $pedido_torta_peso;

                    if (!empty($total_torta)) {
                        $total_torta = str_replace(',', '.', str_replace('.', '', $total_torta));
                    }

                    $total_torta += $calculo;

                    $calculo = number_format($calculo, 2, ',', '.');
                    $total_torta = number_format($total_torta, 2, ',', '.');


                    //    print_r($torta);
                    $jSON["categoria"][] = ["name" => "tortas[{$key}][pedido_torta_valor]", "valor_item" => "{$calculo}", "total_parcial" => "{$total_torta}", "id_total_parcial" => "pedido_torta_valor_total"]; //type = warning danger success        break;



                endif;




            endforeach;


        endif;


        if (!empty($POST['salgados'])):

        //   print_r($POST['salgados']);

        endif;


        if (!empty($POST['doces'])):

        //   print_r($POST['doces']);

        endif;

        if (!empty($POST['refrigerantes'])):

        //   print_r($POST['refrigerantes']);

        endif;



//
//            $Read->ExeRead('pedidos', "WHERE pedido_id =:id", "id={$POST['id']}");
//            $jSON["manager"] = true;
//            $jSON["id"] = $POST['id'];
//            $DADOS = [];
//
//            extract($Read->getResult()[0]);
//
//            $pedido_total = number_format($pedido_total, 2, ',', '.');
//
//            $DADOS['pedido_id'] = $pedido_id;
//            $DADOS['cliente_id'] = $cliente_id;
//            $DADOS['pedido_data_criacao'] = $pedido_data_criacao;
//            $DADOS['pedido_data_retirada'] = $pedido_data_retirada;
//            $DADOS['pedido_total'] = $pedido_total;
//            $DADOS['pedido_status'] = $pedido_status;
//
//
//
//            $jSON["dados"] = $DADOS;
//            $jSON["type"] = "atualizado";



        break;

    case 'create':


        //            $Create->ExeCreate('pedidos', ['pedido_status' => 0]);
//            $jSON["manager"] = true;
//            $jSON["id"] = $Create->getResult();
//            $jSON["type"] = "criado";
        echo "Criado com sucesso";
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
