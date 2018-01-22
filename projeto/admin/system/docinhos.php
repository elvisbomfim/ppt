<main class="main-content bgc-grey-100">
    <div id="mainContent">
        <div class="row gap-20 masonry pos-r" style="position: relative; height: 1128px;">
            <div class="masonry-sizer col-md-6"></div>
            <div class="masonry-item col-md-6" style="position: absolute; left: 0%; top: 0px;">
                <div class="bgc-white p-20 bd">
                    <h6 class="c-grey-900">Cadastro de Docinho</h6>
                    <div class="mT-30">
                        <form action="" method="post">
                            <input type="hidden" name="callback" value="docinhos">
                            <input type="hidden" name="callback_action" value="manager">
                           <div class="form-group">
                                <label>Nome do bolo:</label>
                                <input type="text" name="docinho_nome" class="form-control" id="docinho_nome"  >
                            </div>
                            <div class="form-group">
                                <label>Preço por kg:</label>
                                <input type="text" name="docinho_preco" class="form-control money fix_bug_mask" id="docinho_preco"  >
                            </div>
                            <div class="form-group">
                                <label>Preço kit festa:</label>
                                <input type="text" name="docinho_preco_kit_festa" class="form-control money fix_bug_mask" id="docinho_preco_kit_festa"  >
                            </div>


                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>