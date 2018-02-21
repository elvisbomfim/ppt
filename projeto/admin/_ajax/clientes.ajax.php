<?php

require_once '../../_app/Config.inc.php';
$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//$setPost = array_map('strip_tags', $getPost);
//$POST = array_map('trim', $getPost);

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
    case 'manager':

        if (empty($POST['id'])):
            $Create->ExeCreate('clientes', ['cliente_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:
            $Read->ExeRead('clientes');
//$Read->ExeRead('clientes', "WHERE cliente_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
//var_dump($Read->getResult()[0]);
            $jSON["dados"] = $Read->getResult()[0];
            $jSON["type"] = "atualizado";

        endif;

        break;

    case 'update':

        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        $Update->ExeUpdate('clientes', $POST, 'WHERE cliente_id =:id', "id=$ID");

        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Cliente $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success

        $Read->ExeRead('clientes', "WHERE cliente_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        if ($TYPE == "criado"):
            $Read->ExeRead('clientes', "WHERE cliente_id =:id", "id={$ID}");
            extract($Read->getResult()[0]);
            $jSON["result"] = " <tr id='{$cliente_id}'>
                                    <td>{$cliente_nome}</td>
                                    <td>{$cliente_telefone_1}</td>
                                    <td>{$cliente_telefone_2}</td>
                                    <td> " . ($cliente_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='clientes' data-callback_action='manager' data-id='{$cliente_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='clientes' data-callback_action='delete' data-id='{$cliente_id}' data-name='{$cliente_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = "<td>{$cliente_nome}</td>
                                    <td>{$cliente_telefone_1}</td>
                                    <td>{$cliente_telefone_1}</td>
                                    <td>{$cliente_telefone_2}</td>
                                    <td> " . ($cliente_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='clientes' data-callback_action='manager' data-id='{$cliente_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='clientes' data-callback_action='delete' data-id='{$cliente_id}' data-name='{$cliente_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':
        $Delete->ExeDelete('clientes', 'WHERE cliente_id =:id', "id={$POST["id"]}");
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


if (!empty($_GET['search'])):

    $Read->ExeRead("clientes", "WHERE cliente_nome LIKE :c '%' ", "c={$_GET['search']}");

    if ($Read->getResult()):

        foreach ($Read->getResult() as $cliente):
            extract($cliente);

            $jSON['results'][] = ["id" => $cliente_id, "text" => $cliente_nome];

        endforeach;

    else:
        $jSON['results'] = '';

    endif;

    $jSON["pagination"] = ["more" => true];


endif;

echo json_encode($jSON);
