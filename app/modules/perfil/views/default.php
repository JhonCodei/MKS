<div class="row">
    <div class="card-box col-lg-12">
        <p class="h1 header-title">
            <b class="h2"><?php print "<b>".usuario_nombre(). "</b>";?></b>
            <p class="text-muted m-b-30 font-14">Perfil</p>
        </p>
        <h3 class="h3"> Modificar contrase&ntilde;a</h3>
        <div class="row">
            
            <div class="col-md-2"></div>
            <div class="col-md-3">
                <div class="input-group form-group">
                    <input type="password" class="form-control text-center" id="password_" placeholder=" Nueva contrase&ntilde;a">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group form-group">
                    <input type="password" class="form-control text-center" id="repassword_" placeholder=" Repita la contrase&ntilde;a">
                </div>
            </div>
            &nbsp;
            <div class="col-md-2">
                <button type="button" id="btn-change" class="btn btn-primary waves-effect waves-light col-lg-12" onclick="return cambio_password();">
                    <span class="ti ti-save"></span>&nbsp; Modificar
                </button>
            </div>
        </div>


    </div>
</div>