<?php
ob_start();
session_start();
require_once '/_app/Config.inc.php';

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

$getURL = strip_tags(trim(filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
$setURL = (empty($getURL) ? 'index' : $getURL);
$URL = explode('/', $setURL);
//$SEO = new Seo($setURL);
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

        <?php
        if (file_exists('themes/' . THEME . '/style.css')):
            echo "<link rel='stylesheet' href='" . INCLUDE_PATH . "/style.css'/>";
        endif;


        //WC THEME CSS FILES
        if (file_exists("themes/" . THEME . "/css")):
            foreach (scandir("themes/" . THEME . "/css") as $wcCssThemeFiles) :
                if (file_exists("themes/" . THEME . "/css/{$wcCssThemeFiles}") && !is_dir("themes/" . THEME . "/css/{$wcCssThemeFiles}") && pathinfo("themes/" . THEME . "/css/{$wcCssThemeFiles}")['extension'] == 'css'):
                    echo "<link rel='stylesheet' href='" . INCLUDE_PATH . "/css/{$wcCssThemeFiles}'/>";
                endif;
            endforeach;
        endif;
        ?>







        <?php
        if (file_exists('themes/' . THEME . '/scripts.js')):
            echo '<script src="' . INCLUDE_PATH . '/scripts.js"></script>';
        endif;
        ?>


        <?php
        //WC THEME JS FILES
//        if (file_exists("themes/" . THEME . "/js")):
//            foreach (scandir("themes/" . THEME . "/js") as $wcJsThemeFiles) :
//                if (file_exists("themes/" . THEME . "/js/{$wcJsThemeFiles}") && !is_dir("themes/" . THEME . "/js/{$wcJsThemeFiles}") && pathinfo("themes/" . THEME . "/js/{$wcJsThemeFiles}")['extension'] == 'js'):
//                    echo "<script src='" . INCLUDE_PATH . "/js/{$wcJsThemeFiles}'></script>";
//                endif;
//            endforeach;
//        endif;
        ?>
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
//        if (ADMIN_MAINTENANCE && !empty($_SESSION['userLogin']['user_level']) && $_SESSION['userLogin']['user_level'] >= 6):
//            echo "<div class='workcontrol_maintenance'>&#x267A; O MODO de manutenção está ativo. Somente administradores podem ver o site assim &#x267A;</div>";
//        endif;
//        if (ADMIN_MAINTENANCE && (empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < 6)):
//            require 'maintenance.php';
//        else:
        //PESQUISA
        $Search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ($Search && !empty($Search['s'])):
            $Search = urlencode(strip_tags(trim($Search['s'])));
            header('Location: ' . BASE . '/pesquisa/' . $Search);
        endif;

        //HEADER
        if (file_exists(REQUIRE_PATH . "/includes/header.php")):
            require REQUIRE_PATH . "/includes/header.php";
        else:
            trigger_error('Crie um arquivo /includes/header.php na pasta do tema!');
        endif;

        //CONTENT
        $URL[1] = (empty($URL[1]) ? null : $URL[1]);

        $Pages = array();
//            $Read->FullRead("SELECT page_name FROM " . DB_PAGES);
//            if ($Read->getResult()):
//                foreach ($Read->getResult() as $SinglePage):
//                    $Pages[] = $SinglePage['page_name'];
//                endforeach;
//            endif;

        if (in_array($URL[0], $Pages) && file_exists(REQUIRE_PATH . '/pagina.php') && empty($URL[1])):
            if (file_exists(REQUIRE_PATH . "/page-{$URL[0]}.php")):
                require REQUIRE_PATH . "/page-{$URL[0]}.php";
            else:
                require REQUIRE_PATH . '/pagina.php';
            endif;
        elseif (file_exists(REQUIRE_PATH . '/' . $URL[0] . '.php')):
            if ($URL[0] == 'artigos' && file_exists(REQUIRE_PATH . "/cat-{$URL[1]}.php")):
                require REQUIRE_PATH . "/cat-{$URL[1]}.php";
            else:
                require REQUIRE_PATH . '/' . $URL[0] . '.php';
            endif;
        elseif (file_exists(REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php')):
            require REQUIRE_PATH . '/' . $URL[0] . '/' . $URL[1] . '.php';
        else:
            if (file_exists(REQUIRE_PATH . "/404.php")):
                require REQUIRE_PATH . '/404.php';
            else:
                trigger_error("Não foi possível incluir o arquivo themes/" . THEME . "/{$getURL}.php <b>(O arquivo 404 também não existe!)</b>");
            endif;
        endif;

        //FOOTER
        if (file_exists(REQUIRE_PATH . "/includes/footer.php")):
            require REQUIRE_PATH . "/includes/footer.php";
        else:
            trigger_error('Crie um arquivo /includes/footer.php na pasta do tema!');
        endif;
        //endif;
        ?>
        <script src="<?= BASE ?>/js/jquery.js" type="text/javascript"></script>
        <script src="<?= BASE ?>/js/jquery.form.js" type="text/javascript"></script>
        <script src="<?= INCLUDE_PATH ?> /js/vendor.js"></script>
        <script src="<?= INCLUDE_PATH ?> /js/bundle.js"></script>
        <script src="<?= INCLUDE_PATH ?> /js/motor.js"></script>
        <script src="<?= BASE ?>/js/maskinput.js" type="text/javascript"></script>
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
