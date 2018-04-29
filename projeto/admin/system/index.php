<div class="row page-titles">
    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Painel</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item active">Painel</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">

    </div>
</div>

<?php
$GET = filter_input_array(INPUT_GET, FILTER_DEFAULT);
?>

        <nav class="nav nav-pills nav-fill">
            <a class="nav-item nav-link  <?= $GET['pedidos'] == 'bolo' ? 'active' : '' ?>" style="background: pink; color:black;" href="<?= BASE ?>admin/painel.php?exe=index&pedidos=bolo">Pedidos Bolo</a>
            <a class="nav-item nav-link <?= $GET['pedidos'] == 'torta' ? 'active' : '' ?>" style="background: blue; color:white;" href="<?= BASE ?>admin/painel.php?exe=index&pedidos=torta">Pedidos Torta</a>
            <a class="nav-item nav-link <?= $GET['pedidos'] == 'salgado' ? 'active' : '' ?>" style="background: yellow; color:black;" style="background: pink" href="<?= BASE ?>admin/painel.php?exe=index&pedidos=salgado">Pedidos Salgado</a>
            <a class="nav-item nav-link <?= $GET['pedidos'] == 'doce' ? 'active' : '' ?>" style="background: brown; color:white;" href="<?= BASE ?>admin/painel.php?exe=index&pedidos=doce">Pedidos Doce</a>
        </nav>


<?php
switch ($GET['pedidos']):
    case 'bolo':
        require_once 'pedidos_bolo.php';
        break;
    case 'torta':
        require_once 'pedidos_torta.php';
        break;
    case 'salgado':
        require_once 'pedidos_salgado.php';
        break;
    case 'doce':
        require_once 'pedidos_docinho.php';
        break;
    default :
        require_once 'pedidos_bolo.php';
        break;
endswitch;
?>



