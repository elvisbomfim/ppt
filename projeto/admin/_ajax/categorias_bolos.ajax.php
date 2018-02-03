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

            $Create->ExeCreate('categoria_bolos', ['categoria_bolo_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:

            $Read->ExeRead('categoria_bolos', "WHERE categoria_bolo_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];

            $DADOS = [];

            extract($Read->getResult()[0]);

            $categoria_bolo_preco_kg = number_format($categoria_bolo_preco_kg, 2, ',', '.');
            $categoria_bolo_kit_festa = number_format($categoria_bolo_kit_festa, 2, ',', '.');

            $DADOS['categoria_bolo_id'] = $categoria_bolo_id;
            $DADOS['categoria_bolo_nome'] = $categoria_bolo_nome;
            $DADOS['categoria_bolo_preco_kg'] = $categoria_bolo_preco_kg;
            $DADOS['categoria_bolo_kit_festa'] = $categoria_bolo_kit_festa;
            $DADOS['categoria_bolo_status'] = $categoria_bolo_status;



            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;

        break;

    case 'update':
        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $POST['categoria_bolo_preco_kg'] = str_replace(',', '.', str_replace('.', '', $POST['categoria_bolo_preco_kg']));
        $POST['categoria_bolo_kit_festa'] = str_replace(',', '.', str_replace('.', '', $POST['categoria_bolo_kit_festa']));

        $Update->ExeUpdate('categoria_bolos', $POST, 'WHERE categoria_bolo_id =:id', "id=$ID");

        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Bolo $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success


        $Read->ExeRead('categoria_bolos', "WHERE categoria_bolo_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        $categoria_bolo_preco_kg = number_format($categoria_bolo_preco_kg, 2, ',', '.');
        $categoria_bolo_kit_festa = number_format($categoria_bolo_kit_festa, 2, ',', '.');

        if ($TYPE == "criado"):

            $jSON["result"] = " <tr id='{$categoria_bolo_id}'>
                                    <td>{$categoria_bolo_nome}</td>
                                    <td>{$categoria_bolo_preco_kg}</td>
                                    <td>{$categoria_bolo_kit_festa}</td>
                                    <td> " . ($categoria_bolo_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='categorias_bolos' data-callback_action='manager' data-id='{$categoria_bolo_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='categorias_bolos' data-callback_action='delete' data-id='{$categoria_bolo_id}' data-name='{$categoria_bolo_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$categoria_bolo_nome}</td>
                                    <td>{$categoria_bolo_preco_kg}</td>
                                    <td>{$categoria_bolo_kit_festa}</td>
                                    <td>" . ($categoria_bolo_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='categorias_bolos' data-callback_action='manager' data-id='{$categoria_bolo_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='categorias_bolos' data-callback_action='delete' data-id='{$categoria_bolo_id}' data-name='{$categoria_bolo_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;
        break;
    case 'delete':

        $Delete->ExeDelete('categoria_bolos', 'WHERE categoria_bolo_id =:id', "id={$POST["id"]}");
        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Bolo apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Bolo apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;

echo json_encode($jSON);
