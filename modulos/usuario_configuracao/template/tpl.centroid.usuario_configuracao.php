<div class="row">
    <div class="col-md-30">
        <div class="msg-alert has-error alert alert-danger" role="alert" id="msg" style="margin-top: 8px; text-align: center; font-size: 15px; display: none"></div>
    </div><!-- .col-md-12 -->
</div>

<input type="hidden" value="<?=$latitude?>" name="latitude" id="latitude"/>
<input type="hidden" value="<?=$longitude?>" name="longitude" id="longitude"/>
<div class="row">
    <div class="col-md-30">
        <div>
            <label for="logradouro"><?=RTL_LOGRADOURO_REFERENCIA?></label>
            <input type="text" placeholder="<? echo ROTULO_PLACEHOLDER_BUSCA_ENDERECO ?>" size="120" name="logradouro" id="logradouro" class="form-control" value=""/>
            <input type="hidden" name="logradouro_hidden" id="logradouro_hidden" value=""/>
        </div><br/>
        <div id="map" style="min-height:450px !important"></div>
    </div>
</div>
