<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <h4 class="c-grey-900 mB-20">Clientes <button class="btn btn-primary j_action"  data-callback="clientes" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
                    <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Celular</th>
                                <th>Data de nascimento</th>
                                <th>Cep</th>
                                <th>Rua</th>
                                <th>Bairro</th>
                                <th>Número</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php
                            $read = new Read;

                            $read->FullRead("SELECT * FROM enderecos e " //"pl" é o player, "e" é o eventos e "po" são os pontos
                                    . "INNER JOIN clientes c ON c.cliente_id = e.cliente_id ");

//$read->ExeRead('clientes');

                            foreach ($read->getResult() as $value):
                                extract($value);
                                ?>


                                <tr id="<?= $cliente_id; ?>">
                                    <td><?= $cliente_nome; ?></td>
                                    <td><?= $cliente_telefone_1; ?></td>
                                    <td><?= $cliente_telefone_2; ?></td>
                                    <td><?= $cliente_data_nascimento; ?></td>
                                    <td><?= $endereco_cep; ?></td>
                                    <td><?= $endereco_rua; ?></td>
                                    <td><?= $endereco_bairro; ?></td>
                                    <td><?= $endereco_numero; ?></td>
                                    <td><button class="btn btn-warning j_action" data-callback="clientes" data-callback_action="manager" data-id="<?= $cliente_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger j_action"  data-callback="clientes" data-callback_action="delete" data-id="<?= $cliente_id; ?>"><i class="fa fa-trash-o"></i> Apagar</button></td>
                                </tr>
                                <?php
                            endforeach;
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="formulario" action="" method="post">
                    <input type="hidden" name="callback" value="clientes">
                    <input type="hidden" name="callback_action" value="update">
                    <div class="get_id"></div>
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
                        <input type="text"  name="endereco_cep" class="getCep form-control fix_bug_mask" id="endereco_cep">
                    </div>

                    <div class="form-group">
                        <label>Logradouro</label>
                        <input type="text" class="form-control logradouro" name="endereco_rua" id="endereco_rua">
                    </div>
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" class="form-control" name="endereco_numero" id="endereco_numero">
                    </div>
                    <div class="form-group">
                        <label>Bairro</label>
                        <input type="text" class="form-control bairro" name="endereco_bairro" id="endereco_bairro">
                    </div>
                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" class="form-control complemento" name="endereco_complemento" id="endereco_complemento">
                    </div>
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" class="form-control cidade" name="endereco_cidade" id="endereco_cidade">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" class="form-control uf" name="endereco_estado" id="endereco_estado" >
                    </div>


                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="formulario">Cadastrar</button>
            </div>
        </div>
    </div>
</div>