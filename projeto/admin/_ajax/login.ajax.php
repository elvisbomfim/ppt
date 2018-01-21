<?php
require_once '../../_app/Config.inc.php';

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//exit();

if (empty($POST) || empty($POST['callback_action'])):
    die('Acesso Negado!');
endif;

//$strPost = array_map('strip_tags', $getPost);
//$POST = array_map('trim', $strPost);

$Action = $POST['callback_action'];
$jSON = null;
unset($POST['callback']);
unset($POST['callback_action']);

//usleep(2000);

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;

switch ($Action):

    //CREATE
    case 'login':
       
        $jSON["redirect"] = BASE.'admin/painel.php';
        
    break;


endswitch;

echo json_encode($jSON);
