<form action="#" name="frm_historico_saldo" id="frm_historico_saldo" method="post">
    <input type="hidden" name="id_cliente"  id="id_cliente"   value="<?=$linha['id'];?>"/>
    <div class="form-body">
        <div class="row p-t-20">
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label class="form-label" for="saldo_atual">Saldo Atual</label>
                    <input type="text" name="saldo_atual"  id="saldo_atual"  readonly="readonly" class="form-control " value="<?=number_format($linha['saldo'],'2',',','.');?>"/>
                </div>
            </div>
            <!--/span-->
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label class="form-label" for="operacao">Operacao</label>
                    <select name="operacao" id="operacao" class="form-select">
                        <option value="1">CREDITAR</option>
                        <option value="2">DEBITAR</option>
                    </select>
                    <small class="form-text text-muted"> Preencha o campo  Operacao </small> </div>
            </div>
            <!--/span-->
            <div class="col-md-12 mb-2">
                <div class="form-group">
                    <label class="form-label" for="valor">Valor</label>
                    <input type="text" name="valor"  id="valor"  class="form-control mask-dinheiro " value="<?=$linha['valor'];?>"/>
                    <small class="form-text text-muted"> Preencha o campo  Valor </small> </div>
            </div>
            <!--/span-->
        </div>
</form>

<script>
    $(function (){
        Mascaras();
    })
    function AtualizarSaldoCliente()
    {
        if (Squall.ValidateForm($("#frm_historico_saldo"))) {
            $("#bt_salvar").prop("disabled",true).html("<i class=\"fa fa-spin fa-spinner\"></i> Aguarde...").addClass("btn-warning");
            // ao clicar em salvar enviando dados por post via AJAX
            $.post('index_xml.php?app_comando=saldo_cliente&app_modulo=cliente',
                $("#frm_historico_saldo").serialize(),
                // pegando resposta do retorno do post
                function (response)
                {
                    if (response["codigo"] == 0) {
                        Squall.ToastMsg('success',response["mensagem"]);
                        $('#modal_saldo_clientes').modal('hide');
                        AtualizarGridCliente(0,$("#busca").val());
                    } else {
                        Squall.ToastMsg('warning',response["mensagem"]);
                    }
                    $("#bt_salvar").prop("disabled",false).html("<i class=\"fa fa-check\"></i> Atualizar").removeClass("btn-warning");
                }
                , "json" // definindo retorno para o formato json
            );
        }
    }
</script>