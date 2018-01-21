<?php
require_once '../../../_app/Config.inc.php';

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

usleep(2000);

$Read = new Read;
$Create = new Create;
$Update = new Update;
$Delete = new Delete;

switch ($Action):

    //CREATE
    case 'manager':
 
        $c["cliente_nome"] = $POST["cliente_nome"];
        $c["cliente_cpf"] = $POST["cliente_cpf"];
        $c["cliente_telefone_1"] = $POST["cliente_telefone_1"];
        $c["cliente_telefone_2"] = $POST["cliente_telefone_2"];
        $c["cliente_data_nascimento"] = $POST["cliente_data_nascimento"];
        $c["cliente_observacoes"] = $POST["cliente_observacoes"];
        
        $Create->ExeCreate('clientes',$c);
        
        $e["cliente_id"] = $Create->getResult();
        $e["endereco_cep"] = $POST["endereco_cep"];
        $e["endereco_bairro"] = $POST["endereco_bairro"];
        $e["endereco_rua"] = $POST["endereco_rua"];
        $e["endereco_numero"] = $POST["endereco_numero"];
        $e["endereco_complemento"] = $POST["endereco_complemento"];
        $e["endereco_cidade"] = $POST["endereco_cidade"];
        $e["endereco_estado"] = $POST["endereco_estado"];
        
        $Create->ExeCreate('enderecos',$e);
        
        $jSON["sucesso"] = "Cliente cadastrado com sucesso";
        
    break;


endswitch;

echo json_encode($jSON);
