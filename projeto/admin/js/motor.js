var BASE = 'http://localhost/ppt/projeto/admin/';

$(function () {



    //############## GET CEP
    $('.getCep').change(function () {

        var cep = $(this).val().replace('-', '').replace('.', '');
        if (cep.length === 8) {
            $.get("https://viacep.com.br/ws/" + cep + "/json", function (json) {
                if (!json.erro) {
                    $('.bairro').val(json.bairro);
                    $('.complemento').val(json.complemento);
                    $('.cidade').val(json.localidade);
                    $('.logradouro').val(json.logradouro);
                    $('.uf').val(json.uf);
                }
            }, 'json');
        }
    });

    //AUTOSAVE ACTION
    $('html').on('keyup change', 'form.auto_save', function (e) {
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
            success: function (json) {

//                $.each(json.categoria, function (index, value) {
//                    //$("#" + index).val(value);
//                    console.log(index + '=' + value);
//                });
                if (json.total_geral_pedido) {
                    $.each(json.categoria, function (index, value) {

                        $("input[name='" + value.name + "']").val(value.valor_item);
                        $("#" + value.id_total_parcial).val(value.total_parcial);

                    });
                    $("#pedido_total").val(json.total_geral_pedido);
                }



                if (json.name) {
                    var input = form.find('.wc_name');
                    if (!input.val() || input.val() != json.name) {
                        input.val(json.name);
                    }

                    var inputfield = form.find('input[name*=_name]');
                    if (inputfield) {
                        inputfield.val(json.name);
                    }
                }

                if (json.gallery) {
                    form.find('.gallery').fadeTo('300', '0.5', function () {
                        $(this).html($(this).html() + json.gallery).fadeTo('300', '1');
                    });
                }

                if (json.view) {
                    $('.wc_view').attr('href', json.view);
                }

                if (json.reorder) {
                    $('.wc_drag_active').removeClass('btn_yellow');
                    $('.wc_draganddrop').removeAttr('draggable');
                }

                //CLEAR INPUT FILE
                if (!json.error) {
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
            success: function (json) {
                //REMOVE LOAD

                //EXIBE CALLBACKS
                if (json.alerta) {

                    if (json.alerta.type === 'success') {

                        if ($(".get_id").hasClass("manager")) {
                            $('#exampleModal').modal('hide');
                            $('#dataTable tbody').prepend(json.result);
                        } else {

                            $('#exampleModal').modal('hide');

                            $('#' + json.id).html(json.result);
                        }
                    }


                    $.notify({
                        icon: json.alerta.icon,
                        title: '<strong>' + json.alerta.title + '</strong>',
                        message: json.alerta.message
                    }, {
                        type: json.alerta.type
                    });


                }

                if (json.pedido_form_reset) {
                    $('#callback_action').val('calcular');
                }


                //REDIRECIONA
                if (json.redirect) {
//                        $('.workcontrol_upload p').html("Atualizando dados, aguarde!");
//                        $('.workcontrol_upload').fadeIn().css('display', 'flex');
                    window.setTimeout(function () {
                        window.location.href = json.redirect;
                        if (window.location.hash) {
                            window.location.reload();
                        }
                    }, 1500);
                }

                //INTERAGE COM TINYMCE
                if (json.tinyMCE) {
                    tinyMCE.activeEditor.insertContent(json.tinyMCE);
                    $('.workcontrol_imageupload').fadeOut('slow', function () {
                        $('.workcontrol_imageupload .image_default').attr('src', '../tim.php?src=admin/_img/no_image.jpg&w=500&h=300');
                    });
                }

                //GALLETY UPDATE HTML
                if (json.gallery) {
                    form.find('.gallery').fadeTo('300', '0.5', function () {
                        $(this).html($(this).html() + json.gallery).fadeTo('300', '1');
                    });
                }

                //DATA CONTENT IN j_content
                if (json.content) {
                    $('.j_content').fadeTo('300', '0.5', function () {
                        $(this).html(json.content).fadeTo('300', '1');
                    });
                }

                //DATA DINAMIC CONTENT
                if (json.divcontent) {
                    $(json.divcontent[0]).html(json.divcontent[1]);
                }

                //DATA DINAMIC FADEOUT
                if (json.divremove) {
                    $(json.divremove).fadeOut();
                }

                //DATA CLICK
                if (json.forceclick) {
                    setTimeout(function () {
                        $(json.forceclick).click();
                    }, 250);
                }

                //DATA DOWNLOAD IN j_downloa
                if (json.download) {
                    $('.j_download').fadeTo('300', '0.5', function () {
                        $(this).html(json.download).fadeTo('300', '1');
                    });
                }

                //DATA HREF VIEW
                if (json.view) {
                    $('.wc_view').attr('href', json.view);
                }

                //DATA REORDER
                if (json.reorder) {
                    $('.wc_drag_active').removeClass('btn_yellow');
                    $('.wc_draganddrop').removeAttr('draggable');
                }

                //DATA CLEAR
                if (json.clear) {
                    form.trigger('reset');
                    if (form.find('.label_publish')) {
                        form.find('.label_publish').removeClass('active');
                    }
                }

                //DATA CLEAR INPUT
                if (json.inpuval) {
                    $('.wc_value').val(json.inpuval);
                }

                //CLEAR INPUT FILE
                if (!json.error) {
                    form.find('input[type="file"]').val('');
                }

                //CLEAR NFE XML
                if (json.nfexml) {
                    $('.wc_nfe_xml').html("<a target='_blank' href='" + json.nfexml + "' title='Ver XML'>Ver XML</a>");
                }

                //DATA NFE PDF
                if (json.nfepdf) {
                    $('.wc_nfe_pdf').html("<a target='_blank' href='" + json.nfepdf + "' title='Ver PDF'>Ver PDF</a>");
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
        var Id = $(this).attr('data-id');



        var Callback = $(this).attr('data-callback');


        var Callback_action = $(this).attr('data-callback_action');
        $.post(BASE + '_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, id: Id}, function (json) {

            if (json.alerta) {

                $.notify({
                    icon: json.alerta.icon,
                    title: '<strong>' + json.alerta.title + '</strong>',
                    message: json.alerta.message
                }, {
                    type: json.alerta.type
                });
            }


            if (json.manager) {
                $('#exampleModal').modal('show');

              //  $("#formulario")[0].reset();
                if (json.type == 'atualizado') {
                    $(".get_id").removeClass("manager");
                    $(".btn-action-name").html("<i class='fa fa-edit'></i> Atualizar");

                } else {
                    $(".get_id").addClass("manager");
                    $(".btn-action-name").html("<i class='fa fa-save'></i> Cadastrar");
                }


                $(".get_id").html("<input type='hidden' name='id' value='" + json.id + "' ><input type='hidden' name='type' value='" + json.type + "' >");



                if (json.dados) {

                    $.each(json.dados, function (index, value) {

                        if ($("#" + index).prop("tagName") === 'INPUT') {
                            $("#" + index).val(value);
                        }else if ($("#" + index).prop("tagName") === 'SELECT'){
                            $("#" + index).html("");
                            $("#" + index).html(value);
                        }

console.log($("#" + index).prop("tagName"));
                        //console.log(index + '=' + value);
                    });

                }


            } else {
                $(".get_id").html();

            }


            if (json.remove_tr_id) {
                $("#confirmar-apagar").modal('hide');
                $('#' + json.remove_tr_id).remove();
            }

//            if (json.modal) {
//                $('#J_Modal').modal('show');
//                $('.j_title').html(json.modal_title);
//                $('#Alert_Modal .modal-header').addClass("bg-success");
//                $('.j_content').html(json.modal_content);
//                $('#J_Modal .modal-footer .btn-primary').html("<i class='fa fa-save'></i> Salvar mudanÃ§as");
//                $('#J_Modal .modal-footer .btn-primary').show();
//                if (json.btn_save === false) {
//
//                    $('#J_Modal .modal-footer .btn-primary').hide();
//                } else if (json.btn_save === true) {
//                    $('#J_Modal .modal-footer .btn-primary').show();
//                } else {
//                    $('#J_Modal .modal-footer .btn-primary').html(json.btn_save);
//                }
//
//
//
//                if (json.remove_li) {
//                    $("[delete=" + json.id + "]").remove();
//                }
//
//                $('#chat').animate({scrollTop: 700000000000000000}, 500);
//                if (json.textarea) {
//
//                    $('#textarea').trumbowyg();
//                }
//
//                $('.notificar_nome').change(function () {
//                    $('#notificar_nome').text('OlÃ¡ ' + $(this).val());
//                });
//
//
//            }




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



//abrir modal de confimação
    $('#confirmar-apagar').on('show.bs.modal', function (e) {
        $('.name').html('<strong>' + $(e.relatedTarget).data('name') + '</strong>');
        $(this).find('.btn-excluir').attr('data-id', $(e.relatedTarget).attr('data-id'));
        $(this).find('.btn-excluir').attr('data-callback', $(e.relatedTarget).attr('data-callback'));
        $(this).find('.btn-excluir').attr('data-callback_action', $(e.relatedTarget).attr('data-callback_action'));



    });


});

