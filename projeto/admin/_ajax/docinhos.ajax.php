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

$jSON["tabela"] = "docinhosTable";



switch ($Action):

    //CREATE
    case 'manager':
        if (empty($POST['id'])):
            $Create->ExeCreate('docinhos', ['docinho_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:

            $Read->ExeRead('docinhos', "WHERE docinho_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
            $DADOS = [];

            extract($Read->getResult()[0]);

            $docinho_preco = number_format($docinho_preco, 2, ',', '.');
            $docinho_preco_kit_festa = number_format($docinho_preco_kit_festa, 2, ',', '.');

            $DADOS['docinho_id'] = $docinho_id;
            $DADOS['docinho_nome'] = $docinho_nome;
            $DADOS['docinho_preco'] = $docinho_preco;
            $DADOS['docinho_preco_kit_festa'] = $docinho_preco_kit_festa;
            $DADOS['docinho_status'] = $docinho_status;



            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;
$jSON["idmodal"] = "docinhosModal";
        break;

    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $POST['docinho_preco'] = str_replace(',', '.', str_replace('.', '', $POST['docinho_preco']));
        $POST['docinho_preco_kit_festa'] = str_replace(',', '.', str_replace('.', '', $POST['docinho_preco_kit_festa']));

        $Update->ExeUpdate('docinhos', $POST, 'WHERE docinho_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Doce $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success
        $jSON["idmodal"] = "docinhosModal";
        
        $Read->ExeRead('docinhos', "WHERE docinho_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        $docinho_preco = number_format($docinho_preco, 2, ',', '.');
        $docinho_preco_kit_festa = number_format($docinho_preco_kit_festa, 2, ',', '.');

        if ($TYPE == "criado"):
            $jSON["result"] = " <tr id='{$docinho_id}'>
                                    <td>{$docinho_nome}</td>
                                    <td>{$docinho_preco}</td>
                                    <td>{$docinho_preco_kit_festa}</td>
                                    <td> " . ($docinho_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='docinhos' data-callback_action='manager' data-id='{$docinho_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='docinhos' data-callback_action='delete' data-id='{$docinho_id}' data-name='{$docinho_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$docinho_nome}</td>
                                    <td>{$docinho_preco}</td>
                                    <td>{$docinho_preco_kit_festa}</td>
                                    <td>" . ($docinho_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='docinhos' data-callback_action='manager' data-id='{$docinho_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='docinhos' data-callback_action='delete' data-id='{$docinho_id}' data-name='{$docinho_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':

        $Delete->ExeDelete('docinhos', 'WHERE docinho_id =:id', "id={$POST["id"]}");
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
