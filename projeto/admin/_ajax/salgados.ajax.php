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

sleep(1);

switch ($Action):

    //CREATE
    case 'manager':

        $Create->ExeCreate('salgados', $POST);

        $jSON["sucesso"] = "Salgado cadastrado com sucesso";

        break;
    case 'update':

        break;
    case 'delete':

        $Delete->ExeDelete('salgados', 'WHERE salgado_id =:id', "id={$POST["id"]}");
        if ($Delete->getResult()):
            $jSON["modal"] = true;
            $jSON["modal_title"] = "Salgado deletado com sucesso!";
            $jSON["remove_tr_id"] = $POST["id"];
            else:
               $jSON["modal"] = false;
               $jSON["j_title"] = "Erro ao deletar!"; 
        endif;


        break;


endswitch;

echo json_encode($jSON);
