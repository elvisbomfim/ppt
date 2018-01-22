var BASE = 'http://localhost/ppt/projeto/admin/';

$(function () {
 
   

    //############## GET CEP
    $('.getCep').change(function () {
              
        var cep = $(this).val().replace('-', '').replace('.', '');
        if (cep.length === 8) {
            $.get("https://viacep.com.br/ws/" + cep + "/json", function (data) {
                if (!data.erro) {
                    $('.bairro').val(data.bairro);
                    $('.complemento').val(data.complemento);
                    $('.cidade').val(data.localidade);
                    $('.logradouro').val(data.logradouro);
                    $('.uf').val(data.uf);
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
            url: BASE + '_ajax/' + callback + '.ajax.php',
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
            url: BASE + '_ajax/' + callback + '.ajax.php',
            data: {callback_action: callback_action},
            dataType: 'json',
//            beforeSubmit: function () {
//                form.find('.form_load').fadeIn('fast');
//                $('.trigger_ajax').fadeOut('fast');
//            },
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
                
                    //EXIBE CALLBACKS
                    if (data.trigger) {
                        Trigger(data.trigger);
                    }

                    //REDIRECIONA
                    if (data.redirect) {
//                        $('.workcontrol_upload p').html("Atualizando dados, aguarde!");
//                        $('.workcontrol_upload').fadeIn().css('display', 'flex');
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
    
    
    //CONFIRM ACTION
    $('html, body').on('click', '.j_action', function (e) {
        var Prevent = $(this);
        var Id = $(this).data('id');
        var Callback = $(this).data('callback');
               
        
        var Callback_action = $(this).data('callback_action');
        $.post(BASE + '_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, id: Id}, function (json) {


             if (json.modal) {
                $('#J_Modal').modal('show');
                $('.j_title').html(json.modal_title);
                $('#Alert_Modal .modal-header').addClass("bg-success");
                $('.j_content').html(json.modal_content);
                $('#J_Modal .modal-footer .btn-primary').html("<i class='fa fa-save'></i> Salvar mudanÃ§as");
                $('#J_Modal .modal-footer .btn-primary').show();
                if (json.btn_save === false) {

                    $('#J_Modal .modal-footer .btn-primary').hide();
                } else if (json.btn_save === true) {
                    $('#J_Modal .modal-footer .btn-primary').show();
                } else {
                    $('#J_Modal .modal-footer .btn-primary').html(json.btn_save);
                }

                if (json.remove_tr_id) {
                   
                    $('#' + json.remove_tr_id).remove();
                }

                if (json.remove_li) {
                    $("[delete=" + json.id + "]").remove();
                }

                $('#chat').animate({scrollTop: 700000000000000000}, 500);
                if (json.textarea) {

                    $('#textarea').trumbowyg();
                }

                $('.notificar_nome').change(function () {
                    $('#notificar_nome').text('OlÃ¡ ' + $(this).val());
                });


            }
            
            


            if (json.upload) {
                $.getScript(BASE + "/js/upload_drag_drop.js");
            }

         

           

            //CONTENT UPDATE
            if (json.content) {
                $('.j_content').fadeTo('300', '0.5', function () {
                    $(this).html(json.content).fadeTo('300', '1');
                });
            }

            //INPUT CLEAR
            if (json.inpuval) {
                if (json.inpuval === 'null') {
                    $('.wc_value').val("");
                } else {
                    $('.wc_value').val(json.inpuval);
                }
            }

            //DINAMIC CONTENT
            if (json.divcontent) {
                $(json.divcontent[0]).html(json.divcontent[1]);
            }
        }, 'json');
        e.preventDefault();
        e.stopPropagation();
    });
    

 
});