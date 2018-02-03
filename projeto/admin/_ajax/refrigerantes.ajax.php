<?php

require_once '../../_app/Config.inc.php';
$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$setPost = array_map('strip_tags', $getPost);
$POST = array_map('trim', $setPost);

$Action = $POST['callback_action'];
$Page = $POST['callback'];
unset($POST['callback_action']);
unset($POST['callback']);

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;
$jSON = null;

sleep(1);

switch ($Action):

//CREATE
    case 'manager':
        if (empty($POST['id'])):
            $Create->ExeCreate('refrigerantes', ['refrigerante_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:

            $Read->ExeRead('refrigerantes', "WHERE refrigerante_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
            $DADOS = [];

            extract($Read->getResult()[0]);

            $refrigerante_preco = number_format($refrigerante_preco, 2, ',', '.');
            $refrigerante_preco_kit_festa = number_format($refrigerante_preco_kit_festa, 2, ',', '.');

            $DADOS['refrigerante_id'] = $refrigerante_id;
            $DADOS['refrigerante_nome'] = $refrigerante_nome;
            $DADOS['refrigerante_preco'] = $refrigerante_preco;
            $DADOS['refrigerante_preco_kit_festa'] = $refrigerante_preco_kit_festa;
            $DADOS['refrigerante_status'] = $refrigerante_status;



            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;

        break;
    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $POST['refrigerante_preco'] = str_replace(',', '.', str_replace('.', '', $POST['refrigerante_preco']));
        $POST['refrigerante_preco_kit_festa'] = str_replace(',', '.', str_replace('.', '', $POST['refrigerante_preco_kit_festa']));

        $Update->ExeUpdate('refrigerantes', $POST, 'WHERE refrigerante_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Refrigerante $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success

        $Read->ExeRead('refrigerantes', "WHERE refrigerante_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        $refrigerante_preco = number_format($refrigerante_preco, 2, ',', '.');
        $refrigerante_preco_kit_festa = number_format($refrigerante_preco_kit_festa, 2, ',', '.');

        if ($TYPE == "criado"):
            $jSON["result"] = " <tr id='{$refrigerante_id}'>
                                    <td>{$refrigerante_nome}</td>
                                    <td>{$refrigerante_preco}</td>
                                    <td>{$refrigerante_preco_kit_festa}</td>
                                    <td> " . ($refrigerante_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='refrigerantes' data-callback_action='manager' data-id='{$refrigerante_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='refrigerantes' data-callback_action='delete' data-id='{$refrigerante_id}' data-name='{$refrigerante_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$refrigerante_nome}</td>
                                    <td>{$refrigerante_preco}</td>
                                    <td>{$refrigerante_preco_kit_festa}</td>
                                    <td>" . ($refrigerante_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='refrigerantes' data-callback_action='manager' data-id='{$refrigerante_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='refrigerantes' data-callback_action='delete' data-id='{$refrigerante_id}' data-name='{$refrigerante_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':

        $Delete->ExeDelete('refrigerantes', 'WHERE refrigerante_id =:id', "id={$POST["id"]}");
        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Refrigerante apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Refrigerante apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;

echo json_encode($jSON);
