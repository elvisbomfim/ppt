<?php
ob_start();
session_start();
require_once '../_app/Config.inc.php';


$getexe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
//$WC_THEME = filter_input(INPUT_GET, "wctheme", FILTER_DEFAULT);
//if ($WC_THEME && $WC_THEME != 'null'):
//    $_SESSION['WC_THEME'] = $WC_THEME;
//    header("Location: " . BASE);
//elseif ($WC_THEME && $WC_THEME == 'null'):
//    unset($_SESSION['WC_THEME']);
//    header("Location: " . BASE);
//endif;
//READ CLASS AUTO INSTANCE
if (empty($Read)):
    $Read = new Read;
endif;



//USER SESSION VALIDATION
if (!empty($_SESSION['userLogin']) && !empty($_SESSION['userLogin']['user_id'])):
    if (empty($Read)):
        $Read = new Read;
    endif;
    $Read->ExeRead(DB_USERS, "WHERE user_id = :user_id", "user_id={$_SESSION['userLogin']['user_id']}");
    if ($Read->getResult()):
        $_SESSION['userLogin'] = $Read->getResult()[0];
    else:
        unset($_SESSION['userLogin']);
    endif;
endif;
?><!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Projeto da torta</title>

        <!-- Bootstrap Core CSS -->
        <link href="<?= BASE; ?>admin/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>


        <!-- Custom CSS -->
        <link href="<?= BASE; ?>admin/css/select2.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?= BASE; ?>admin/css/style.css" rel="stylesheet">
        <link href="<?= BASE; ?>admin/css/custom.css" rel="stylesheet">

        <!-- You can change the theme colors from here -->
        <link href="<?= BASE; ?>admin/css/colors/blue.css" id="theme" rel="stylesheet">
        <link href="<?= BASE; ?>admin/css/estilo-cupom.css" rel="stylesheet">
        <link href="<?= BASE; ?>admin/assets/plugins/DataTables/datatables.min.css" rel="stylesheet" type="text/css"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    </head>
    <body class="fix-header fix-sidebar card-no-border"> 
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
        </div>
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <div id="main-wrapper">
            <?php
            include_once 'includes/header.php';
            include_once 'includes/menu.php';
            ?>

            <div class="page-wrapper">
                <!-- ============================================================== -->
                <!-- Container fluid  -->
                <!-- ============================================================== -->
                <div class="container-fluid">
                    <!-- ============================================================== -->
                    <!-- Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->

                    <!-- ============================================================== -->
                    <!-- End Bread crumb and right sidebar toggle -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Start Page Content -->
                    <!-- ============================================================== -->

                    <?php
                    //QUERY STRING
                    if (!empty($getexe)):
                        $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . strip_tags(trim($getexe) . '.php');
                    else:
                        $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'index.php';
                    endif;

                    if (file_exists($includepatch)):
                        require_once($includepatch);
                    else:
                        echo "<div class=\"content notfound\">";
                        //"<b>Erro ao incluir tela:</b> Erro ao incluir o controller /{$getexe}.php!", WS_ERROR);
                        echo "</div>";
                    endif;
                    ?>


                </div>
            </div>
        </div>


        <!-- Modal AJAX-->

        <div class="modal fade" id="confirmar-apagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Confimar Exclusão</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                    </div>

                    <div class="modal-body">
                        <p>Você está deletando: <span class="name"></span>, esse procedimento é irreversível.</p>
                        <p>Você gostaria realmente de prosseguir?</p>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Não</button>
                        <button class="btn btn-danger btn-excluir j_action"><i class="fa fa-trash-o"></i> Sim</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="<?= BASE ?>js/jquery.js" type="text/javascript"></script>
        <script src="<?= BASE ?>js/jquery.form.js" type="text/javascript"></script>
        <!-- All Jquery -->
        <!-- ============================================================== -->
        <!-- Bootstrap tether Core JavaScript -->
        <script src="<?= BASE ?>admin/assets/plugins/bootstrap/js/tether.min.js"></script>
        <script src="<?= BASE ?>admin/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?= BASE ?>admin/assets/plugins/DataTables/datatables.min.js" type="text/javascript"></script>
        <!-- slimscrollbar scrollbar JavaScript -->
        <script src="<?= BASE ?>admin/js/jquery.slimscroll.js"></script>
        <!--Wave Effects -->
        <script src="<?= BASE ?>admin/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="<?= BASE ?>admin/js/sidebarmenu.js"></script>
        <!--stickey kit -->
        <script src="<?= BASE ?>admin/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <!--Custom JavaScript -->
        <script src="<?= BASE ?>admin/js/custom.min.js"></script>
        <!-- ============================================================== -->
        <!-- This page plugins -->
        <!-- ============================================================== -->
        <!-- Flot Charts JavaScript -->

        <!-- ============================================================== -->
        <!-- Style switcher -->
        <!-- ============================================================== -->
        <script src="<?= BASE ?>admin/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
        <script src="<?= BASE ?>admin/js/adicionar-input.js?=v<?= time() ?>" type="text/javascript"></script>
        <script src="<?= BASE ?>admin/js/motor.js?=v<?= time() ?>"></script>
        <script src="<?= BASE ?>admin/js/maskinput.js" type="text/javascript"></script>
        <script src="<?= BASE ?>js/bootstrap-notify.min.js" type="text/javascript"></script>

        <script src="<?= BASE ?>admin/js/select2.min.js" type="text/javascript"></script>



        <script>
            //datatables
            $(document).ready(function () {
                $('.tablePPT').DataTable({
                    "order": [[0, "desc"]],
                    responsive: true,
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    }
                });
            });


            //mascara
            $(document).ready(function () {
                $('.getCep').mask('00000-000');
                $('.cpf').mask('000.000.000-00', {reverse: true});
                $('.phone').mask('(00) 00000-0000');
                $('.money').mask('000.000.000.000.000,00', {reverse: true});
            });


            $('.fix_bug_mask').keydown(function () {
                this.selectionStart = this.selectionEnd = this.value.length;


            });

            //buscador de clintes no select do modal
            $('.cliente_nome_id').select2({

                language: {
                    errorLoading: function () {
                        return 'Os resultados não puderam ser carregados.';
                    },
                    inputTooLong: function (args) {
                        var overChars = args.input.length - args.maximum;

                        var message = 'Apague ' + overChars + ' caracter';

                        if (overChars != 1) {
                            message += 'es';
                        }

                        return message;
                    },
                    inputTooShort: function (args) {
                        var remainingChars = args.minimum - args.input.length;

                        var message = 'Digite ' + remainingChars + ' ou mais caracteres';

                        return message;
                    },
                    loadingMore: function () {
                        return 'Carregando mais resultados…';
                    },
                    maximumSelected: function (args) {
                        var message = 'Você só pode selecionar ' + args.maximum + ' ite';

                        if (args.maximum == 1) {
                            message += 'm';
                        } else {
                            message += 'ns';
                        }

                        return message;
                    },
                    noResults: function () {

                        return 'Nenhum resultado encontrado';

                    },
                    searching: function () {
                        return 'Buscando…';
                    }

                },

                placeholder: 'Digite o nome do cliente',
                dropdownParent: $('#pedidosModal'),
                ajax: {
                    url: BASE + '_ajax/clientes.ajax.php',
                    dataType: 'json',
                    quietMillis: 100,
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    },
                    results: function (data, page) {
                        return {results: data.results}
                        ;
                    }
                },

            });




            //          $(document).ready(function () {
            //               $('#dataTable').DataTable(
//                        "language": {
//                            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
//                        }
            //              );
            //          });
        </script>
        
        <!-- Modal -->
<div class="modal fade" id="cupomfiscalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form>
                    <div class="class-cupom"></div>

                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="">Imprimir</button>
            </div>
        </div>
    </div>
</div>
    </body>

</html>
<?php
ob_end_flush();
