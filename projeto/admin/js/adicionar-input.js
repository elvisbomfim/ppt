$(function () {

    var refri = "add_refrigerante";
    var doce = "add_docinho";
    var salgado = "add_salgado";
    var e_refri = "excluir-refrigerante";
    var e_doce =  "excluir-docinho";
    var e_salgado = "excluir-salgado";
    localStorage.clear();
    
    //var add = $('.add').attr("id");

    $('#doce-tab').on('show.bs.tab', function (e) {
        
        if (!localStorage.getItem('doce')) {
            AddInputs(doce, e_doce);
        }
        localStorage.setItem('doce', "existe");
        //localStorage.clear();
    });

    $('#refrigerante-tab').on('show.bs.tab', function (e) {
        if (!localStorage.getItem('refrigerante')) {
            AddInputs(refri, e_refri);
        }
        localStorage.setItem('refrigerante', "existe");
        //localStorage.clear();
        
    });

    $('#salgado-tab').on('show.bs.tab', function (e) {
        
        if (!localStorage.getItem('salgado')) {
            AddInputs(salgado, e_salgado);
        }
        localStorage.setItem('salgado', "existe");
        //localStorage.clear();
       
    });

    function AddInputs(add, excluir) {

        var campos_max = 30;   //max de 10 campos

        var x = 0;
        
        $('#' + add).click(function (e) {
            e.preventDefault();     //prevenir novos clicks
            // alert($(this).closest(".tab-pane").attr("id"));
            alert("Add: "+x);
            var tab = $(this).closest(".tab-pane").attr("id");

            if (x == 0) {
                $("#" + tab).find('.listas:first').show();
                x++;
            } else {
                if (x < campos_max) {

                    var clone = $("#" + tab).find('.listas:first').clone();

                    $("#" + tab).find('.nova_lista').append(clone);


                    x++;
                }
            }



        });
        // Remover o div anterior
        $('body').on("click", "."+excluir, function (e) {
            e.preventDefault();
            alert("Deleta: "+x);
            if (x > 1) {
                $(this).parent('.listas').remove();

            } else {
                $(this).parent('.listas').hide();

            }
            x--;

        });
    }



});



//$(function () {
//    localStorage.clear();
//    $('#doce-tab').on('show.bs.tab', function (e) {
//
//        var activeTab = localStorage.getItem('doceTab');
//
//        if (activeTab) {
//            alert('já existo e  não vou chamar mais a ');
//        } else {
//            var Doce = new AddInputs();
//            localStorage.setItem('doceTab', Doce.getDados());
//        }
//
//
//    });
//
//    $('#refrigerante-tab').on('show.bs.tab', function (e) {
//        var activeTab = localStorage.getItem('refrigeranteTab');
//
//        if (activeTab) {
//            alert(activeTab);
//        } else {
//            var Refrigerante = new AddInputs();
//            localStorage.setItem('refrigeranteTab', Refrigerante.getDados());
//        }
//    });
//
//    $('#salgado-tab').on('show.bs.tab', function (e) {
//        var activeTab = localStorage.getItem('salgadoTab');
//
//        if (activeTab) {
//            alert(activeTab);
//        } else {
//            var Salgado = new AddInputs();
//
//            localStorage.setItem('salgadoTab', Salgado.getDados());
//        }
//    });
//
//
//    function AddInputs() {
//
//
//        var Contador;
//        var Campos_max;
//
//
//        this.SetContador = function () {
//            this.Contador = 0;
//        }
//
//        this.SetCampos_max = function () {
//            this.Campos_max = 30;
//        }
//
//        this.getDados = function () {
//
//            $('.add_field').click(function (e) {
//                e.preventDefault();     //prevenir novos clicks
//                // alert($(this).closest(".tab-pane").attr("id"));
//
//                var tab = $(this).closest(".tab-pane").attr("id");
//
//                if (this.Contador === 0) {
//                    $("#" + tab).find('.listas:first').show();
//                    this.Contador++;
//
//
//                } else {
//                    if (this.Contador < this.Campos_max) {
//
//                        var clone = $("#" + tab).find('.listas:first').clone();
//
//                        $("#" + tab).find('.nova_lista').append(clone);
//
//
//                        this.Contador++;
//
//
//                    }
//                }
//
//
//
//            });
//            // Remover o div anterior
//            $('body').on("click", ".remover_campo", function (e) {
//                e.preventDefault();
//
//                if (this.Contador > 1) {
//                    $(this).parent('.listas').remove();
//
//                } else {
//                    $(this).parent('.listas').hide();
//
//                }
//                this.Contador--;
//
//            });
//
//
//        };
//
//    }
//    ;
//
//});