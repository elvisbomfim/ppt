/* 
 * Biblioteca de evendos jQuery do Painel Work Control Pro Content Manager
 * Created on : 06/01/2016, 11:15:16
 * Author     : UpInside Treinamentos
 */

$(function () {
 
   

    //############## GET CEP
    $('.wc_getCep').change(function () {
        var cep = $(this).val().replace('-', '').replace('.', '');
        if (cep.length === 8) {
            $.get("https://viacep.com.br/ws/" + cep + "/json", function (data) {
                if (!data.erro) {
                    $('.wc_bairro').val(data.bairro);
                    $('.wc_complemento').val(data.complemento);
                    $('.wc_localidade').val(data.localidade);
                    $('.wc_logradouro').val(data.logradouro);
                    $('.wc_uf').val(data.uf);
                }
            }, 'json');
        }
    });

    //AUTOSAVE ACTION
    $('html').on('change', 'form.auto_save', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var form = $(this);
        var callback = form.find('input[name="callback"]').val();
        var callback_action = form.find('input[name="callback_action"]').val();

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.triggerSave();
        }

        form.ajaxSubmit({
            url: '_ajax/' + callback + '.ajax.php',
            data: {callback_action: callback_action},
            dataType: 'json',
            uploadProgress: function (evento, posicao, total, completo) {
                var porcento = completo + '%';
                $('.workcontrol_upload_progrees').text(porcento);

                if (completo <= '80') {
                    $('.workcontrol_upload').fadeIn().css('display', 'flex');
                }
                if (completo >= '99') {
                    $('.workcontrol_upload').fadeOut('slow', function () {
                        $('.workcontrol_upload_progrees').text('0%');
                    });
                }
                //PREVENT TO RESUBMIT IMAGES GALLERY
                form.find('input[name="image[]"]').replaceWith($('input[name="image[]"]').clone());
            },
            success: function (data) {
                if (data.name) {
                    var input = form.find('.wc_name');
                    if (!input.val() || input.val() != data.name) {
                        input.val(data.name);
                    }

                    var inputfield = form.find('input[name*=_name]');
                    if (inputfield) {
                        inputfield.val(data.name);
                    }
                }

                if (data.gallery) {
                    form.find('.gallery').fadeTo('300', '0.5', function () {
                        $(this).html($(this).html() + data.gallery).fadeTo('300', '1');
                    });
                }

                if (data.view) {
                    $('.wc_view').attr('href', data.view);
                }

                if (data.reorder) {
                    $('.wc_drag_active').removeClass('btn_yellow');
                    $('.wc_draganddrop').removeAttr('draggable');
                }

                //CLEAR INPUT FILE
                if (!data.error) {
                    form.find('input[type="file"]').val('');
                }
            }
        });
    });

    //Coloca todos os formulários em AJAX mode e inicia LOAD ao submeter!
    $('html').on('submit', 'form:not(.ajax_off)', function () {
        var form = $(this);
        var callback = form.find('input[name="callback"]').val();
        var callback_action = form.find('input[name="callback_action"]').val();

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.triggerSave();
        }

        form.ajaxSubmit({
            url: '_ajax/' + callback + '.ajax.php',
            data: {callback_action: callback_action},
            dataType: 'json',
            beforeSubmit: function () {
                form.find('.form_load').fadeIn('fast');
                $('.trigger_ajax').fadeOut('fast');
            },
            uploadProgress: function (evento, posicao, total, completo) {
                var porcento = completo + '%';
                $('.workcontrol_upload_progrees').text(porcento);

                if (completo <= '80') {
                    $('.workcontrol_upload').fadeIn().css('display', 'flex');
                }
                if (completo >= '99') {
                    $('.workcontrol_upload').fadeOut('slow', function () {
                        $('.workcontrol_upload_progrees').text('0%');
                    });
                }
                //PREVENT TO RESUBMIT IMAGES GALLERY
                form.find('input[name="image[]"]').replaceWith($('input[name="image[]"]').clone());
            },
            success: function (data) {
                //REMOVE LOAD
                form.find('.form_load').fadeOut('slow', function () {
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        Trigger(data.trigger);
                    }

                    //REDIRECIONA
                    if (data.redirect) {
                        $('.workcontrol_upload p').html("Atualizando dados, aguarde!");
                        $('.workcontrol_upload').fadeIn().css('display', 'flex');
                        window.setTimeout(function () {
                            window.location.href = data.redirect;
                            if (window.location.hash) {
                                window.location.reload();
                            }
                        }, 1500);
                    }

                    //INTERAGE COM TINYMCE
                    if (data.tinyMCE) {
                        tinyMCE.activeEditor.insertContent(data.tinyMCE);
                        $('.workcontrol_imageupload').fadeOut('slow', function () {
                            $('.workcontrol_imageupload .image_default').attr('src', '../tim.php?src=admin/_img/no_image.jpg&w=500&h=300');
                        });
                    }

                    //GALLETY UPDATE HTML
                    if (data.gallery) {
                        form.find('.gallery').fadeTo('300', '0.5', function () {
                            $(this).html($(this).html() + data.gallery).fadeTo('300', '1');
                        });
                    }

                    //DATA CONTENT IN j_content
                    if (data.content) {
                        $('.j_content').fadeTo('300', '0.5', function () {
                            $(this).html(data.content).fadeTo('300', '1');
                        });
                    }

                    //DATA DINAMIC CONTENT
                    if (data.divcontent) {
                        $(data.divcontent[0]).html(data.divcontent[1]);
                    }

                    //DATA DINAMIC FADEOUT
                    if (data.divremove) {
                        $(data.divremove).fadeOut();
                    }

                    //DATA CLICK
                    if (data.forceclick) {
                        setTimeout(function () {
                            $(data.forceclick).click();
                        }, 250);
                    }

                    //DATA DOWNLOAD IN j_downloa
                    if (data.download) {
                        $('.j_download').fadeTo('300', '0.5', function () {
                            $(this).html(data.download).fadeTo('300', '1');
                        });
                    }

                    //DATA HREF VIEW
                    if (data.view) {
                        $('.wc_view').attr('href', data.view);
                    }

                    //DATA REORDER
                    if (data.reorder) {
                        $('.wc_drag_active').removeClass('btn_yellow');
                        $('.wc_draganddrop').removeAttr('draggable');
                    }

                    //DATA CLEAR
                    if (data.clear) {
                        form.trigger('reset');
                        if (form.find('.label_publish')) {
                            form.find('.label_publish').removeClass('active');
                        }
                    }

                    //DATA CLEAR INPUT
                    if (data.inpuval) {
                        $('.wc_value').val(data.inpuval);
                    }

                    //CLEAR INPUT FILE
                    if (!data.error) {
                        form.find('input[type="file"]').val('');
                    }

                    //CLEAR NFE XML
                    if (data.nfexml) {
                        $('.wc_nfe_xml').html("<a target='_blank' href='" + data.nfexml + "' title='Ver XML'>Ver XML</a>");
                    }

                    //DATA NFE PDF
                    if (data.nfepdf) {
                        $('.wc_nfe_pdf').html("<a target='_blank' href='" + data.nfepdf + "' title='Ver PDF'>Ver PDF</a>");
                    }
                });
            }
        });
        return false;
    });

    //Ocultra Trigger clicada
    $('html').on('click', '.trigger_ajax, .trigger_modal', function () {
        $(this).fadeOut('slow', function () {
            $(this).remove();
        });
    });

    //Publish Effect
    $('.label_publish').click(function () {
        if (!$(this).find('input').is(':checked')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    });

 
});