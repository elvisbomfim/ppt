<?php

require_once '../../_app/Config.inc.php';
$getPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$setPost = array_map('strip_tags', $getPost);
$POST = array_map('trim', $setPost);

//var_dump($POST);

$Action = $POST['callback_action'];
$Page = $POST['callback'];
unset($POST['callback_action']);
unset($POST['callback']);

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;
$jSON = null;

$jSON["tabela"] = "salgadosTable";


switch ($Action):

//CREATE
    case 'manager':
        if (empty($POST['id'])):

            $Create->ExeCreate('salgados', ['salgado_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";

        else:


            $Read->ExeRead('salgados', "WHERE salgado_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];

            $DADOS = [];

            extract($Read->getResult()[0]);

            $salgado_preco = number_format($salgado_preco, 2, ',', '.');
            $salgado_kit_festa = number_format($salgado_kit_festa, 2, ',', '.');

            $DADOS['salgado_id'] = $salgado_id;
            $DADOS['salgado_nome'] = $salgado_nome;
            $DADOS['salgado_preco'] = $salgado_preco;
            $DADOS['salgado_kit_festa'] = $salgado_kit_festa;
            $DADOS['salgado_status'] = $salgado_status;

            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";


        endif;
$jSON["idmodal"] = "salgadosModal";
        break;
    case 'update':
        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $POST['salgado_preco'] = str_replace(',', '.', str_replace('.', '', $POST['salgado_preco']));
        $POST['salgado_kit_festa'] = str_replace(',', '.', str_replace('.', '', $POST['salgado_kit_festa']));

        $Update->ExeUpdate('salgados', $POST, 'WHERE salgado_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Salgado $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success        break;
        $jSON["idmodal"] = "salgadosModal";
        $Read->ExeRead('salgados', "WHERE salgado_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        $salgado_preco = number_format($salgado_preco, 2, ',', '.');
        $salgado_kit_festa = number_format($salgado_kit_festa, 2, ',', '.');

        if ($TYPE == "criado"):
            $jSON["result"] = " <tr id='{$salgado_id}'>
                                    <td>{$salgado_nome}</td>
                                    <td>{$salgado_preco}</td>
                                    <td>{$salgado_kit_festa}</td>
                                    <td> " . ($salgado_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='salgados' data-callback_action='manager' data-id='{$salgado_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='salgados' data-callback_action='delete' data-id='{$salgado_id}' data-name='{$salgado_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$salgado_nome}</td>
                                    <td>{$salgado_preco}</td>
                                    <td>{$salgado_kit_festa}</td>
                                    <td>" . ($salgado_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='salgados' data-callback_action='manager' data-id='{$salgado_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='salgados' data-callback_action='delete' data-id='{$salgado_id}' data-name='{$salgado_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;



        break;
    case 'delete':



        $Delete->ExeDelete('salgados', 'WHERE salgado_id =:id', "id={$POST["id"]}");
        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Salgado apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Salgado apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;

echo json_encode($jSON);
