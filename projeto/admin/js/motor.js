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

                if (json.resetInputCalcular) {
                    $('#callback_action').val('calcular');
                }


                if (json.total_geral_pedido) {
                    if (json.pedido_outros_valor_total) {
                        $("#pedido_outros_valor_total").val(json.pedido_outros_valor_total);
                    }
                    $("#pedido_total").val(json.total_geral_pedido);

                    $.each(json.categoria, function (index, value) {
                        if (value.valor_item !== "") {
                            $("input[name='" + value.name + "']").val(value.valor_item);
                        }

                        $("#" + value.id_total_parcial).val(value.total_parcial);

                    });

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

                if (json.idmodalcupom) {

                    $('#' + json.idmodalcupom).modal('show');
                    $('.class-cupom').html(json.resultcupom);
                }

                //EXIBE CALLBACKS
                if (json.alerta) {

                    if (json.alerta.type === 'success') {


                        if ($(".get_id").hasClass("manager")) {
                            $('#' + json.idmodal).modal('hide');
                            $('#' + json.tabela + ' tbody').prepend(json.result);
                        } else {
                            $('#' + json.idmodal).modal('hide');

                            $('#' + json.id).html(json.result);
                        }



                    }

                    if (json.resultPedidoCreate) {
                        $('#' + json.tabela + ' tbody').prepend(json.resultPedidoCreate);

                    } else if (json.resultPedidoUpdate) {
                        $('#' + json.id).html(json.resultPedidoUpdate);
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
        var dados = $(this).attr('data-dados');
        var Callback = $(this).attr('data-callback');
        var Callback_action = $(this).attr('data-callback_action');
        $.post(BASE + '_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, id: Id, dados: dados}, function (json) {

            if (json.idmodalcupom) {

                $('#' + json.idmodalcupom).modal('show');
                $('.class-cupom').html(json.resultcupom);
            }

            if (json.alerta) {

                if (json.resultPedidoCreate) {
//ADicionar linha duplicada na tabela
                    var t = $('.tablePPT').DataTable();

                    t.row.add([
                        json.resultPedidoCreate.pedido_id,
                        json.resultPedidoCreate.cliente_nome,
                        json.resultPedidoCreate.pedido_data_criacao,
                        json.resultPedidoCreate.pedido_data_retirada,
                        json.resultPedidoCreate.pedido_total,
                        json.resultPedidoCreate.pedido_status,
                        json.resultPedidoCreate.botoes
                    ]).draw(false);

                    // $('#' + json.tabela + ' tbody').prepend(json.resultPedidoCreate);

                } else if (json.resultPedidoUpdate) {
                    $('#' + json.id).html(json.resultPedidoUpdate);
                }



                $.notify({
                    icon: json.alerta.icon,
                    title: '<strong>' + json.alerta.title + '</strong>',
                    message: json.alerta.message
                }, {
                    type: json.alerta.type
                });


            }




            if (json.manager) {
                $('#' + json.idmodal).modal('show');

                //  $("#formulario")[0].reset();
                if (json.type == 'atualizado') {
                    $(".get_id").removeClass("manager");
                    $(".btn-action-name").html("<i class='fa fa-edit'></i> Atualizar");

                } else {
                    $(".get_id").addClass("manager");
                    $(".btn-action-name").html("<i class='fa fa-save'></i> Cadastrar");
                }


                $(".get_id").html("<input type='hidden' name='id' value='" + json.id + "' ><input type='hidden' name='type' value='" + json.type + "' >");


                //json para edição dos formulários menos do pedidos
                if (json.dados) {

                    $.each(json.dados, function (index, value) {

                        if ($("#" + index).prop("tagName") === 'INPUT') {
                            $("#" + index).val(value);
                        } else if ($("#" + index).prop("tagName") === 'SELECT') {
                            $("#" + index).html("");
                            $("#" + index).html(value);
                        } else if ($("#" + index).prop("tagName") === 'TEXTAREA') {
                            $("#" + index).val(value);
                        }

                        console.log($("#" + index).prop("tagName"));
                        //console.log(index + '=' + value);
                    });

                }

                //json para edição dos pedidos
                if (json.dadospedidos) {
                    $('#' + json.idmodal).find('form')[0].reset();
                    $(".selects-pedidos").find("option:first").prop('selected', true);
                    $("#cliente_nome_select").html(json.dadospedidos.cliente_nome_id);
                    $(".pedido_data_criacao").val(json.dadospedidos.pedido_data_criacao);
                    $(".pedido_data_retirada").val(json.dadospedidos.pedido_data_retirada);
                    $("#pedido_bolo_valor_total").val(json.dadospedidos.pedido_bolo_valor_total);
                    $("#pedido_torta_valor_total").val(json.dadospedidos.pedido_torta_valor_total);
                    $("#pedido_salgado_valor_total").val(json.dadospedidos.pedido_salgado_valor_total);
                    $("#pedido_doce_valor_total").val(json.dadospedidos.pedido_docinho_valor_total);
                    $("#pedido_refrigerante_valor_total").val(json.dadospedidos.pedido_refrigerante_valor_total);
                    $("#pedido_outros_valor_total").val(json.dadospedidos.pedido_outros_valor_total);
                    $("#pedido_total").val(json.dadospedidos.pedido_total);
                    if (json.dadospedidos.pedido_is_kit_festa === '1') {
                        $("#kit_festa").prop('checked', true);
                    }


                    $('#callback_action').val('calcular');

                    if (!localStorage.getItem('bolo')) {
                        AddInputs("add_bolo", "excluir-bolo");
                        localStorage.setItem('bolo', "existe");
                    }

                    if (!localStorage.getItem('doce')) {
                        AddInputs("add_docinho", "excluir-docinho");
                        localStorage.setItem('doce', "existe");
                    }

                    if (!localStorage.getItem('refrigerante')) {

                        AddInputs("add_refrigerante", "excluir-refrigerante");
                        localStorage.setItem('refrigerante', "existe");
                    }

                    if (!localStorage.getItem('salgado')) {

                        AddInputs("add_salgado", "excluir-salgado");
                        localStorage.setItem('salgado', "existe");
                    }

                    if (!localStorage.getItem('torta')) {

                        AddInputs("add_torta", "excluir-torta");
                        localStorage.setItem('torta', "existe");
                    }
                    
                    if (!localStorage.getItem('outros')) {

                        AddInputs("add_outros", "excluir-outros");
                        localStorage.setItem('outros', "existe");
                    }

                    //contador para a condição para o gatilho do bolo
                    if (json.dadospedidos.bolos) {
                        count_bolo = (json.dadospedidos.bolos.length - 1);

                        $.each(json.dadospedidos.bolos, function (index, value) {

                            //condição para o gatilho do bolo
                            if (count_bolo > index) {
                                $("#add_bolo").trigger('click');
                            }


                            //console.log(value[index]);

                            $.each(json.dadospedidos.bolos[index], function (index_sub, value_sub) {

                                //console.log(index_sub);
                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        // "input:radio[name='" + index_sub + "']:not(:disabled):first"
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);
                                        // alert("0");
                                    } else {
                                        //alert("1");
                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }
                                //  console.log(typeof $("input[name='" + index_sub + "']").prop("tagName"));

                                if (tipo === "number" || tipo === "text") {
//                            $("." + index).val(value);

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                            } else if ("input[name='" + index_sub + "']").prop("tagName") === 'SELECT') {
//                                $("input[name='" + index_sub + "']").html("");
//                                $("input[name='" + index_sub + "']").html(value_sub[0]);

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                    //$("[name='" + index_sub + "']").html(value_sub);



                                    if ($.isArray(value_sub)) {
                                        $.each(value_sub, function (index_recheio, value_recheio) {
                                            $("[name='" + index_sub + "[]']").eq(index_recheio).html(value_recheio);
                                        });

                                        $.each(value_sub, function (index_recheio_comum, value_recheio_comum) {
                                            $("[name='" + index_sub + "[]'][value='" + value_recheio_comum + "']").prop('checked', true);
                                        });


                                    }


                                }

                            });

                        });
                    }

                    if (json.dadospedidos.tortas) {
                        count_torta = (json.dadospedidos.tortas.length - 1);

                        $.each(json.dadospedidos.tortas, function (index, value) {

                            if (count_torta > index) {
                                $("#add_torta").trigger('click');
                            }

                            $.each(json.dadospedidos.tortas[index], function (index_sub, value_sub) {




                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);

                                    } else {

                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }

                                if (tipo === "number" || tipo === "text") {

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                  

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);

                                }

                            });

                        });
                    }
                    if (json.dadospedidos.doces) {
                        count_doce = (json.dadospedidos.doces.length - 1);

                        $.each(json.dadospedidos.doces, function (index, value) {

                            if (count_doce > index) {
                                $("#add_docinho").trigger('click');
                            }

                            $.each(json.dadospedidos.doces[index], function (index_sub, value_sub) {




                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);

                                    } else {

                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }

                                if (tipo === "number" || tipo === "text") {

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                  

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);

                                }

                            });

                        });
                    }

                    if (json.dadospedidos.salgados) {
                        count_salgado = (json.dadospedidos.salgados.length - 1);

                        $.each(json.dadospedidos.salgados, function (index, value) {

                            if (count_salgado > index) {
                                $("#add_salgado").trigger('click');
                            }

                            $.each(json.dadospedidos.salgados[index], function (index_sub, value_sub) {




                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);

                                    } else {

                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }

                                if (tipo === "number" || tipo === "text") {

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                  

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);

                                }

                            });

                        });
                    }

                    if (json.dadospedidos.refrigerantes) {
                        count_refrigerante = (json.dadospedidos.refrigerantes.length - 1);

                        $.each(json.dadospedidos.refrigerantes, function (index, value) {

                            if (count_refrigerante > index) {
                                $("#add_refrigerante").trigger('click');
                            }

                            $.each(json.dadospedidos.refrigerantes[index], function (index_sub, value_sub) {




                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);

                                    } else {

                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }

                                if (tipo === "number" || tipo === "text") {

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                  

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);

                                }

                            });

                        });
                    }
                    
                    if (json.dadospedidos.outros) {
                        count_outros = (json.dadospedidos.outros.length - 1);

                        $.each(json.dadospedidos.outros, function (index, value) {

                            if (count_outros > index) {
                                $("#add_outros").trigger('click');
                            }

                            $.each(json.dadospedidos.outros[index], function (index_sub, value_sub) {




                                var tipo = $("input[name='" + index_sub + "']").attr("type");

                                console.log("tipo e " + $("input[name='" + index_sub + "']").attr("type"));
                                if (tipo === "radio") {
                                    if (value_sub === '0') {
                                        $("[name='" + index_sub + "'][value='0']").attr('checked', true);

                                    } else {

                                        $("[name='" + index_sub + "'][value='1']").attr('checked', true);
                                    }
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);
                                }

                                if (tipo === "number" || tipo === "text") {

                                    $("input[name='" + index_sub + "']").val(value_sub);

//                  

                                }

                                if (typeof tipo === "undefined") {
                                    $("." + index_sub).html("");
                                    $("[name='" + index_sub + "']").html(value_sub);

                                }

                            });

                        });
                    }
                    //                  console.log(index + '=' + value);



                }


            } else {
                $(".get_id").html();

            }


            if (json.remove_tr_id) {
                $("#confirmar-apagar").modal('hide');
                $('#' + json.remove_tr_id).remove();
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
            //DINAMIC CONTENT
            if (json.divcontent) {
                $(json.divcontent[0]).html(json.divcontent[1]);
            }
        }, 'json');
        e.preventDefault();
        e.stopPropagation();
    });

    $('html, body').on('change', '.j_select', function (e) {
        var Prevent = $(this);
        var value = $(this).val(); //pegar valor do select
        var id = $(this).attr('data-id');
        var key = $(this).attr('data-key');
        var Callback = $(this).attr('data-callback');
        var Callback_action = $(this).attr('data-callback_action');
        $.post(BASE + '/_ajax/' + Callback + '.ajax.php', {callback: Callback, callback_action: Callback_action, id: id, key: key, value: value}, function (json) {


            //REDIRECIONA
            if (json.redirect) {
                // $('.workcontrol_upload p').html("Atualizando dados, aguarde!");
                // $('.workcontrol_upload').fadeIn().css('display', 'flex');
                window.setTimeout(function () {
                    window.location.href = json.redirect;
                    if (window.location.hash) {
                        window.location.reload();
                    }
                }, 1500);
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



//ABRIR MULTIPLOS MODAIS

(function ($, window) {
    'use strict';

    var MultiModal = function (element) {
        this.$element = $(element);
        this.modalCount = 0;
    };

    MultiModal.BASE_ZINDEX = 1040;

    MultiModal.prototype.show = function (target) {
        var that = this;
        var $target = $(target);
        var modalIndex = that.modalCount++;

        $target.css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20) + 10);

        // Bootstrap triggers the show event at the beginning of the show function and before
        // the modal backdrop element has been created. The timeout here allows the modal
        // show function to complete, after which the modal backdrop will have been created
        // and appended to the DOM.
        window.setTimeout(function () {
            // we only want one backdrop; hide any extras
            if (modalIndex > 0)
                $('.modal-backdrop').not(':first').addClass('hidden');

            that.adjustBackdrop();
        });
    };

    MultiModal.prototype.hidden = function (target) {
        this.modalCount--;

        if (this.modalCount) {
            this.adjustBackdrop();

            // bootstrap removes the modal-open class when a modal is closed; add it back
            $('body').addClass('modal-open');
        }
    };

    MultiModal.prototype.adjustBackdrop = function () {
        var modalIndex = this.modalCount - 1;
        $('.modal-backdrop:first').css('z-index', MultiModal.BASE_ZINDEX + (modalIndex * 20));
    };

    function Plugin(method, target) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('multi-modal-plugin');

            if (!data)
                $this.data('multi-modal-plugin', (data = new MultiModal(this)));

            if (method)
                data[method](target);
        });
    }

    $.fn.multiModal = Plugin;
    $.fn.multiModal.Constructor = MultiModal;

    $(document).on('show.bs.modal', function (e) {
        $(document).multiModal('show', e.target);
    });

    $(document).on('hidden.bs.modal', function (e) {
        $(document).multiModal('hidden', e.target);
    });
}(jQuery, window));
