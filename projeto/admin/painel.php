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

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title></title>

        <link rel="base" href="<?= BASE; ?>"/>



        <link rel="shortcut icon" href="<?= INCLUDE_PATH; ?>/images/favicon.png"/>

        <style>
            #loader {
                transition: all 0.3s ease-in-out;
                opacity: 1;
                visibility: visible;
                position: fixed;
                height: 100vh;
                width: 100%;
                background: #fff;
                z-index: 90000;
            }

            #loader.fadeOut {
                opacity: 0;
                visibility: hidden;
            }

            .spinner {
                width: 40px;
                height: 40px;
                position: absolute;
                top: calc(50% - 20px);
                left: calc(50% - 20px);
                background-color: #333;
                border-radius: 100%;
                -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
                animation: sk-scaleout 1.0s infinite ease-in-out;
            }

            @-webkit-keyframes sk-scaleout {
                0% { -webkit-transform: scale(0) }
                100% {
                    -webkit-transform: scale(1.0);
                    opacity: 0;
                }
            }

            @keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0);
                    transform: scale(0);
                } 100% {
                    -webkit-transform: scale(1.0);
                    transform: scale(1.0);
                    opacity: 0;
                }
            }
        </style>
        
        <link href="style.css" rel="stylesheet">

    </head>
    <body class="app">  

        <div id='loader'>
            <div class="spinner"></div>
        </div>

        <script type="text/javascript">
            window.addEventListener('load', () => {
                const loader = document.getElementById('loader');
                setTimeout(() => {
                    loader.classList.add('fadeOut');
                }, 300);
            });
        </script>

        <?php 
        
        include_once 'includes/menu.php';
        
        ?>
        <div class="page-container">
        <?php
                include_once 'includes/header.php';
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
            
        <script src="<?= BASE ?>/js/jquery.js" type="text/javascript"></script>
        <script src="<?= BASE ?>/js/jquery.form.js" type="text/javascript"></script>
        <script src="<?= BASE ?>/admin/js/vendor.js"></script>
        <script src="<?= BASE ?>/admin/js/bundle.js"></script>
        <script src="<?= BASE ?>/admin/js/motor.js"></script>
        <script src="<?= BASE ?>/admin/js/maskinput.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('.getCep').mask('00000-000');
                $('.cpf').mask('000.000.000-00', {reverse: true});
                $('.phone').mask('(00) 00000-0000');
            });
            
            $('.fix_bug_mask').keydown(function() {
            this.selectionStart = this.selectionEnd = this.value.length;
            
            
        })
        
        </script>
    </body>

</html>
<?php
ob_end_flush();
