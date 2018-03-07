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

$jSON["tabela"] = "coberturasTable";



switch ($Action):

    //CREATE
    case 'manager':
        if (empty($POST['id'])):
            $Create->ExeCreate('coberturas', ['cobertura_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:

            $Read->ExeRead('coberturas', "WHERE cobertura_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
            $DADOS = [];

            extract($Read->getResult()[0]);

            if (!empty($cobertura_preco_kg)):
                $cobertura_preco_kg = number_format($cobertura_preco_kg, 2, ',', '.');
            else:
                $cobertura_preco_kg = '0,00';
            endif;


            $DADOS['cobertura_id'] = $cobertura_id;
            $DADOS['cobertura_nome'] = $cobertura_nome;
            $DADOS['cobertura_preco_kg'] = $cobertura_preco_kg;
            $DADOS['cobertura_status'] = $cobertura_status;

            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;
$jSON["idmodal"] = "coberturasModal";
        break;

    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        if (!empty($POST['cobertura_preco_kg'])):
            $POST['cobertura_preco_kg'] = str_replace(',', '.', str_replace('.', '', $POST['cobertura_preco_kg']));
        else:
            unset($POST['cobertura_preco_kg']);
        endif;


        $Update->ExeUpdate('coberturas', $POST, 'WHERE cobertura_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Cobertura $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success
        $jSON["idmodal"] = "coberturasModal";
        
        $Read->ExeRead('coberturas', "WHERE cobertura_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);


        if (!empty($cobertura_preco_kg)):
            $cobertura_preco_kg = number_format($cobertura_preco_kg, 2, ',', '.');
        else:
            $cobertura_preco_kg = '0,00';
        endif;

        if ($TYPE == "criado"):
            $jSON["result"] = " <tr id='{$cobertura_id}'>
                                    <td>{$cobertura_nome}</td>
                                    <td>{$cobertura_preco_kg}</td>
                                    <td> " . ($cobertura_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='coberturas' data-callback_action='manager' data-id='{$cobertura_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='coberturas' data-callback_action='delete' data-id='{$cobertura_id}' data-name='{$cobertura_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$cobertura_nome}</td>
                                    <td>{$cobertura_preco_kg}</td>
                                    <td>" . ($cobertura_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='coberturas' data-callback_action='manager' data-id='{$cobertura_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='coberturas' data-callback_action='delete' data-id='{$cobertura_id}' data-name='{$cobertura_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':

        $Delete->ExeDelete('coberturas', 'WHERE cobertura_id =:id', "id={$POST["id"]}");
        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Cobertura apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Cobertura apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;

echo json_encode($jSON);
