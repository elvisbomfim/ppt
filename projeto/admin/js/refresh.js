function atualizarPedidos() {
 



    var callback_action = $('table').attr("data-callback_action");
    var pedido_nome = $('table').attr("data-pedido-nome");
    var contador = $('.contador').val();
    
    
       var currentLocation = window.location.href;
       
       
if(BASE + "painel.php?exe=index&pedidos="+pedido_nome === currentLocation){

$.post( BASE + '_ajax/manager_pedidos.ajax.php', {callback:'manager_pedidos', callback_action: callback_action, pedido_nome: pedido_nome, contador: contador})
  .done(function(json) {
      json = $.parseJSON( json );
     //REDIRECIONA
      console.log(json.redirect);
            if (json.redirect) {
                
                
                    window.location.href = json.redirect;
                    if (window.location.hash) {
                        window.location.reload();
                    }
            }
  }, "json");

}
}




setInterval(function () {
    atualizarPedidos();
}, 5000);
