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

$jSON["tabela"] = "recheiosTable";



switch ($Action):

    //CREATE
    case 'manager':
        if (empty($POST['id'])):
            $Create->ExeCreate('recheios', ['recheio_nome' => '']);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";
        else:

            $Read->ExeRead('recheios', "WHERE recheio_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
            $DADOS = [];

            extract($Read->getResult()[0]);
            if (!empty($recheio_preco_kg)):
                $recheio_preco_kg = number_format($recheio_preco_kg, 2, ',', '.');
            else:
                $recheio_preco_kg = '0,00';
            endif;


            $DADOS['recheio_id'] = $recheio_id;
            $DADOS['recheio_nome'] = $recheio_nome;
            $DADOS['recheio_preco_kg'] = $recheio_preco_kg;
            $DADOS['recheio_tipo'] = $recheio_tipo;
            $DADOS['recheio_status'] = $recheio_status;



            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;
$jSON["idmodal"] = "recheiosModal";
        break;

    case 'update':



        $jSON["result"] = null;
        $ID = $POST['id'];
        $TYPE = $POST['type'];
        unset($POST['id'], $POST['type']);

        if (!empty($POST['recheio_preco_kg'])):
            $POST['recheio_preco_kg'] = str_replace(',', '.', str_replace('.', '', $POST['recheio_preco_kg']));
        else:
            unset($POST['recheio_preco_kg']);
        endif;


        $Update->ExeUpdate('recheios', $POST, 'WHERE recheio_id =:id', "id=$ID");
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Recheio $TYPE com sucesso", "URL" => "", "Target" => "_blank", "type" => "success"]; //type = warning danger success
        $jSON["idmodal"] = "recheiosModal";
        $Read->ExeRead('recheios', "WHERE recheio_id =:id", "id={$ID}");
        extract($Read->getResult()[0]);

        if (!empty($recheio_preco_kg)):
            $recheio_preco_kg = number_format($recheio_preco_kg, 2, ',', '.');
        else:
            $recheio_preco_kg = '0,00';
        endif;

        if ($TYPE == "criado"):

            $jSON["result"] = " <tr id='{$recheio_id}'>
                                    <td>{$recheio_nome}</td>
                                    <td>{$recheio_preco_kg}</td>
                                    <td>" . ($recheio_tipo == 0 ? 'Normal' : 'Especial') . "</td>
                                    <td> " . ($recheio_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='recheios' data-callback_action='manager' data-id='{$recheio_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='recheios' data-callback_action='delete' data-id='{$recheio_id}' data-name='{$recheio_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>
                                </tr>";
        else:
            $jSON["id"] = $ID;
            $jSON["result"] = " <td>{$recheio_nome}</td>
                                    <td>{$recheio_preco_kg}</td>
                                    <td>" . ($recheio_tipo == 0 ? 'Normal' : 'Especial') . "</td>
                                    <td>" . ($recheio_status == 1 ? 'Ativo' : 'Inativo' ) . "</td>
                                    <td><button class='btn btn-warning j_action' data-callback='recheios' data-callback_action='manager' data-id='{$recheio_id}'><i class='fa fa-edit'></i> Editar</button> <button class='btn btn-danger'  data-callback='recheios' data-callback_action='delete' data-id='{$recheio_id}' data-name='{$recheio_nome}' data-toggle='modal' data-target='#confirmar-apagar'><i class='fa fa-trash-o'></i> Apagar</button></td>";

        endif;

        break;
    case 'delete':

        $Delete->ExeDelete('recheios', 'WHERE recheio_id =:id', "id={$POST["id"]}");
        $jSON["remove_tr_id"] = $POST["id"];
        $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Recheio apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        if ($Delete->getResult()):
            $jSON["remove_tr_id"] = $POST["id"];
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Recheio apagado com sucesso", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;

        else:
            $jSON["alerta"] = ["icon" => "fa fa-check", "title" => "", "message" => "Não foi possível apagar", "URL" => "", "Target" => "_blank", "type" => "info"]; //type = warning danger success        break;
        endif;


        break;


endswitch;

echo json_encode($jSON);
