<div class="row page-titles">

    <div class="col-md-6 col-8 align-self-center">
        <h3 class="text-themecolor m-b-0 m-t-0">Clientes</h3>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Painel</a></li>
            <li class="breadcrumb-item active">Gerenciamento de Clientes</li>
        </ol>
    </div>
    <div class="col-md-6 col-4 align-self-center">
    <h4 class="c-grey-900 mB-20"> <button class="btn btn-success j_action pull-right" data-callback="clientes" data-callback_action="manager"><i class="fa fa-plus"></i> Cadastrar novo</button></h4>
            
        
    </div>

</div>

<main class="main-content bgc-grey-100">
    <div id="mainContent">

        <div class="row">
            <div class="col-md-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <table id="clientesTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
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

$read->ExeRead('clientes', "ORDER BY cliente_id DESC");

                            foreach ($read->getResult() as $value):
                                extract($value);
                            if(empty($cliente_nome)):
                                
                                $Delete = new Delete;
                            
                            $Delete->ExeDelete('clientes', "WHERE cliente_id =:id", "id={$cliente_id}");
                                
                                else:
                            
                                ?>


                                <tr id="<?= $cliente_id; ?>">
                                    <td><?= $cliente_nome; ?></td>
                                    <td><?= $cliente_telefone_1; ?></td>
                                    <td><?= $cliente_telefone_2; ?></td>
                                    <td><?= $cliente_data_nascimento; ?></td>
                                    <td><?= $cliente_cep; ?></td>
                                    <td><?= $cliente_rua; ?></td>
                                    <td><?= $cliente_bairro; ?></td>
                                    <td><?= $cliente_numero; ?></td>
                                    <td><button class="btn btn-warning j_action" data-callback="clientes" data-callback_action="manager" data-id="<?= $cliente_id; ?>"><i class="fa fa-edit"></i> Editar</button> <button class="btn btn-danger j_action"  data-callback="clientes" data-callback_action="delete" data-id="<?= $cliente_id; ?>"><i class="fa fa-trash-o"></i> Apagar</button></td>
                                </tr>
                                <?php
                                
                                endif;
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
<div class="modal fade" id="clientesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nome do Cliente:</label>
                                <input type="text" name="cliente_nome" class="form-control" id="cliente_nome"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cpf:</label>
                                <input type="text" name="cliente_cpf" class="form-control cpf fix_bug_mask" id="cliente_cpf"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Telefone:</label>
                                <input type="text" name="cliente_telefone_1" class="form-control phone fix_bug_mask" id="cliente_telefone_1"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Celular:</label>
                                <input type="text" name="cliente_telefone_2" class="form-control phone fix_bug_mask" id="cliente_telefone_2"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data de nascimento:</label>
                                <input type="date" name="cliente_data_nascimento" class="form-control" id="cliente_data_nascimento"  >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Observações:</label>

                                <textarea name="cliente_observacoes" class="form-control" id="cliente_observacoes"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cep: </label>
                                <input type="text"  name="cliente_cep" class="getCep form-control fix_bug_mask" id="cliente_cep">

                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" class="form-control logradouro" name="cliente_rua" id="cliente_rua">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" name="cliente_numero" id="cliente_numero">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" class="form-control bairro" name="cliente_bairro" id="cliente_bairro">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Complemento</label>
                                <input type="text" class="form-control complemento" name="cliente_complemento" id="cliente_complemento">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control cidade" name="cliente_cidade" id="cliente_cidade">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control uf" name="cliente_estado" id="cliente_estado" >
                            </div>
                        </div>
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