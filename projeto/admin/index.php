<?php
ob_start();
session_start();
require_once '../_app/Config.inc.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Sign In</title>
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
        </style> <link href="style.css" rel="stylesheet">
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
        $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
        if (!empty($get)):
            if ($get == 'restrito'):
                $error = '<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!';
            elseif ($get == 'logoff'):
                $error = '<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte sempre!';
            endif;
        endif;
        ?>


        <div class="peers ai-s fxw-nw h-100vh">
            <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style='background-image: url("assets/static/images/bg.jpg")'>
                <div class="pos-a centerXY">
                    <div class="bgc-white bdrs-50p pos-r" style='width: 120px; height: 120px;'>
                        <img class="pos-a centerXY" src="assets/static/images/logo.png" alt="">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style='min-width: 320px;'>
                <h4 class="fw-300 c-grey-900 mB-40">Login</h4>
                <form action="" method="POST">
                    <input type="hidden" name="callback" value="login"/>
                    <input type="hidden" name="callback_action" value="login"/>
                    <div class="form-group">
                        <label class="text-normal text-dark">Login</label>
                        <input type="text" name="user_login" class="form-control" placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label class="text-normal text-dark">Password</label>
                        <input type="password" name="user_senha" class="form-control" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <div class="peers ai-c jc-sb fxw-nw">
                            <div class="peer">
                                <div class="checkbox checkbox-circle checkbox-info peers ai-c">
                                    <input type="checkbox" id="inputCall1" name="inputCheckboxesCall" class="peer">
                                    <label for="inputCall1" class=" peers peer-greed js-sb ai-c">
                                        <span class="peer peer-greed">Remember Me</span>
                                    </label>
                                </div>
                            </div>
                            <div class="peer">
                                <button class="btn btn-primary">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="<?= BASE ?>/js/jquery.js" type="text/javascript"></script>
        <script src="<?= BASE ?>/js/jquery.form.js" type="text/javascript"></script>
        <script src="<?= BASE ?>/admin/js/vendor.js"></script>
        <script src="<?= BASE ?>/admin/js/bundle.js"></script>
        <script src="<?= BASE ?>/admin/js/motor.js"></script>
    </body>
</html>
