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

sleep(1);

switch ($Action):

    //CREATE
    case 'manager':
        
        
        
        $Read->ExeRead('docinhos', "WHERE docinho_id =:id", "id={$POST['doces']['docinho_id'][]}");
        
        print_r($POST['doces']['docinho_id'][]);
        
        
        $jSON['tot_docinho'] = $Read->getResult()[0]['docinho_preco'];

        if (empty($POST['id'])):
            $Create->ExeCreate('pedidos', ['pedido_status' => 0]);
            $jSON["manager"] = true;
            $jSON["id"] = $Create->getResult();
            $jSON["type"] = "criado";            
        else:

            $Read->ExeRead('pedidos', "WHERE pedido_id =:id", "id={$POST['id']}");
            $jSON["manager"] = true;
            $jSON["id"] = $POST['id'];
            $DADOS = [];

            $jSON["dados"] = $DADOS;
            $jSON["type"] = "atualizado";

        endif;

        break;


endswitch;

echo json_encode($jSON);
