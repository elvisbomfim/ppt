$(function () {


    $('#pedidosModal').on('show.bs.modal', function () {
        $("#pedidos-form")[0].reset();
        $(".selects-pedidos option:first").prop('selected',true);
        //$('#pedidos-form option[selected="selected"]').each(function() {
        //    $(this).removeAttr('selected');
        //});
        
        $('.cliente_nome_id').html("");
         $('.nav-tabs a:first').tab('show');
        localStorage.setItem('abriu_bolo', "");
        localStorage.setItem('abriu_torta', "");
        localStorage.setItem('abriu_salgado', "");
        localStorage.setItem('abriu_doce', "");
        localStorage.setItem('abriu_refrigerante', "");
        localStorage.setItem('abriu_outros', "");

        if (!localStorage.getItem('bolo')) {

//            x = localStorage.getItem('contador_x_bolo_add_bolo');
//            vetor = localStorage.getItem('contador_vetor_bolo_add_bolo');

            AddInputs(bolo, e_bolo, abriu_bolo);
            localStorage.setItem('bolo', "existe");
        }



        if (!localStorage.getItem('doce')) {
            AddInputs(doce, e_doce, abriu_doce);
            localStorage.setItem('doce', "existe");
        }

        if (!localStorage.getItem('refrigerante')) {

            AddInputs(refri, e_refri, abriu_refrigerante);
            localStorage.setItem('refrigerante', "existe");
        }

        if (!localStorage.getItem('salgado')) {

            AddInputs(salgado, e_salgado, abriu_salgado);
            localStorage.setItem('salgado', "existe");
        }

        if (!localStorage.getItem('torta')) {

//            x = localStorage.getItem('contador_x_bolo_add_torta');
//            vetor = localStorage.getItem('contador_vetor_bolo_add_torta');

            AddInputs(torta, e_torta, abriu_torta);
            localStorage.setItem('torta', "existe");
        }
        
        if (!localStorage.getItem('outros')) {
            AddInputs(outros, e_outros, abriu_outros);
            localStorage.setItem('outros', "existe");
        }

    });


    $('#pedidosModal').on('hidden.bs.modal', function () {
        $("#pedidos-form")[0].reset();
      
        // $(".tab-pane").each(function () {
        $(this).find(".nova_lista").html("");
         $('.nav-tabs a:first').tab('show');
        // $(this).find('.listas').hide();
        // });
        $('.cliente_nome_id').html("");

        $('#callback_action').val('calcular');

        //localStorage.clear();

        x = 0;
        vetor = 1;
    });
    
    
       
    $('body').on('click', '.get_action_name', function (){
        $('.action_name').html($(this).attr('data-action-name')); 
    });
    
    
    $('.cadastrar-pedido').on('click', function () {
        $('#callback_action').val($('.action_name').html());
        
    });

    $('.btn-add-novo-pedido').on('click', function () {

        $('#callback_action').val('calcular');

        //  if (!localStorage.getItem('bolo')) {
        //      AddInputs(bolo, e_bolo);
        //      localStorage.setItem('bolo', "existe");
        //  }

    });



    var refri = "add_refrigerante";
    var doce = "add_docinho";
    var salgado = "add_salgado";
    var torta = "add_torta";
    var bolo = "add_bolo";
    var outros = "add_outros";

    var e_refri = "excluir-refrigerante";
    var e_doce = "excluir-docinho";
    var e_salgado = "excluir-salgado";
    var e_torta = "excluir-torta";
    var e_bolo = "excluir-bolo";
    var e_outros = "excluir-outros";

    var abriu_bolo = "abriu_bolo";
    var abriu_torta = "abriu_torta";
    var abriu_salgado = "abriu_salgado";
    var abriu_refrigerante = "abriu_refrigerante";
    var abriu_doce = "abriu_doce";
    var abriu_outros = "abriu_outros";

    localStorage.clear();

    //var add = $('.add').attr("id");



    window.AddInputs = function (add, excluir, abriu) {

        var campos_max = 30;   //max de 10 campos

        var x = 0;
        var vetor = 1;

        $('#' + add).click(function (e) {
            e.preventDefault();     //prevenir novos clicks
            // alert($(this).closest(".tab-pane").attr("id"));
            //alert("Add: "+x);
            if (!localStorage.getItem(abriu)) {
                //alert("entro");
//            } else {
                x = 0;
                vetor = 1;
                localStorage.setItem(abriu, "sim");
            }
            var tab = $(this).closest(".tab-pane").attr("id");
            //console.log("dentro da função: " + x);
            // if (x >= 0) {
            // $("#" + tab).find('.listas:first').show();
//                x++;
//            } else {
            if (x < campos_max) {

                var clone = $("#" + tab).find('.listas:first').clone();

                $("#" + tab).find('.nova_lista').prepend(clone);

                if (add === 'add_bolo') {

                    $("#" + tab).find('.nova_lista .listas:first .card-header').attr('id', 'heading-' + vetor);
                    $("#" + tab).find('.nova_lista .listas:first .btn-link').attr('data-target', '#collapse-' + vetor).attr('aria-controls', 'collapse-' + vetor).html('Bolo #' + (vetor + 1));
                    $("#" + tab).find('.nova_lista .listas:first .collapse').attr('aria-labelledby', 'heading-' + vetor).attr('id', 'collapse-' + vetor);
                    $("#" + tab).find('.nova_lista .listas:first .collapse').attr('class', 'collapse');
                    $("#" + tab).find('.nova_lista .listas:first .excluir-bolo').attr('data-id-button', vetor);


                }

                var inputs_novos = $("#" + tab).find('.nova_lista .listas:first :input');

                $.each(inputs_novos, function (index, value) {
                    // $("#" + index).val(value);
                    // alert($(this).attr('name'));



                    if (typeof ($(this).attr('name')) !== 'undefined') {

                        var numsStr = $(this).attr('name').replace(/[^0-9]/g, '');
                        var numero = parseInt(numsStr);





                        var name = $(this).attr('name').split('[' + numero + ']');

                        console.log("name", name[0] + "[" + vetor + "]" + name[1]);

                        $(this).attr("name", name[0] + "[" + vetor + "]" + name[1]);

//Limpar inputs checkbox e select




                       if (typeof ($(this).attr('type')) === 'undefined') {
                            $(this).prop('selectedIndex', 0);
                             
                            // console.log("to no undefined");
                        } else if ($(this).attr('type') === 'number') {
                            // console.log("to no number");
                            $(this).val(1);
                        } else if ($(this).attr('type') === 'checkbox') {
                            $(this).prop('checked', false);
                        } else if ($(this).attr('type') === 'radio') {
                            //console.log($(this));
                            //$(this).prop('checked', false);
                        } else {
                            // console.log("to no text");
                            //alert($(this));
                            $(this).val("");
                        }

                    }
                    //console.log(index + '=' + value);
                });

                x++;
                vetor++;

            }
            //}



           

        });
        // Remover o div anterior
        $('body').on("click", "." + excluir, function (e) {
            e.preventDefault();
            //alert("Deleta: "+x);$(this).parents('.listas').find('input[name=bolos[0][categoria_bolo_id]').val()
            if (x > 0) {

                if ($(this).data("id-button") !== 0) {

                    $(this).parents('.listas').remove();
                    x--;
                    vetor--;
                }

//            } else {
//                $(this).parent('.listas').hide();

            }




        });
    }



});