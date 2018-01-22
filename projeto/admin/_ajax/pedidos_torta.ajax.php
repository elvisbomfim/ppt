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
$jSON=null;

sleep(1);

switch ($Action):
    
    //CREATE
    case 'manager':
 
        $Create->ExeCreate('pedidos_torta',$POST);
        
        $jSON["sucesso"] = "Pedidos de torta cadastrado com sucesso";
        
    break;


endswitch;

echo json_encode($jSON);