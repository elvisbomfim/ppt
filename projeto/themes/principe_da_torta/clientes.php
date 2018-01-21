<main class="main-content bgc-grey-100">
    <div id="mainContent">
        <div class="row gap-20 masonry pos-r" style="position: relative; height: 1128px;">
            <div class="masonry-sizer col-md-6"></div>
            <div class="masonry-item col-md-6" style="position: absolute; left: 0%; top: 0px;">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Basic Form</h6>
                    <div class="mT-30">
                        <form action="" method="post">
                            <input type="hidden" name="callback" value="clientes">
                            <input type="hidden" name="callback_action" value="manager">
                            <div class="form-group">
                                <label>Nome do Cliente:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Cpf:</label>
                                <input type="text" name="cliente_cpf" class="form-control cpf fix_bug_mask" id="cliente_cpf"  >
                            </div>
                            <div class="form-group">
                                <label>Telefone:</label>
                                <input type="text" name="cliente_telefone_1" class="form-control phone fix_bug_mask" id="cliente_telefone_1"  >
                            </div>
                            <div class="form-group">
                                <label>Celular:</label>
                                <input type="text" name="cliente_telefone_2" class="form-control phone fix_bug_mask" id="cliente_telefone_2"  >
                            </div>
                            <div class="form-group">
                                <label>Data de nascimento:</label>
                                <input type="date" name="cliente_data_nascimento" class="form-control" id="cliente_data_nascimento"  >
                            </div>
                            <div class="form-group">
                                <label>Observações:</label>
                                
                                <textarea name="cliente_observacoes" class="form-control" id="cliente_observacoes"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Cep: </label>
                                <input type="text"  name="endereco_cep" class="getCep form-control fix_bug_mask" >
                            </div>

                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" class="form-control logradouro" name="endereco_rua" >
                            </div>
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" name="endereco_numero" >
                            </div>
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" class="form-control bairro" name="endereco_bairro" >
                            </div>
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" class="form-control complemento" name="endereco_complemento" >
                            </div>
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control cidade" name="endereco_cidade" >
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control uf" name="endereco_estado" >
                            </div>


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>