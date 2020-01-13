

function openModal(dato) {
    dato = dato.split("##");
    codeequi = dato[0];
    codihard = dato[1];
    codasi = dato[2];
    codhard = dato[3];
    codhard = dato[3];
    $("#txtcodequimodal").val(codeequi);
    $("#txtcodihardmodal").val(codihard);
    $("#txtcodasimodal").val(codasi);
    $("#txtcodhardModal").val(codhard);
    $("#myModal1").modal('show');

}

function openModaldevo(datadevo) {

    var data = datadevo.split("##");
    codeasi = data[0];
    numasi = data[1];
    $("#txtcodasidvModal").val(codeasi);
    $("#txtcodnumasidvModal").val(numasi);
    $("#myModalDevo").modal('show');
}

function openModalDelePieza(deletpieza) {
    var dato = deletpieza.split("##");
    codihardp = dato[0];
    codidhard = dato[1];
    codiprop = dato[2];

    $("#textcodihar").val(codihardp);
    $("#textcoddhar").val(codidhard);
    $("#textcodipro").val(codiprop);

    $("#myModalDevP").modal('show');

}
function openModalDelpro(delepro) {
    var dato = delepro.split("##");
    codiped = dato[0];
    codidped = dato[1];
    codipro = dato[2];
    nompro = dato[3];
    $("#textcodpedi").val(codiped);
    $("#textcoddpedi").val(codidped);
    $("#textcodipro").val(codipro);
    $("#textnompro").val(nompro);
    $("#myModalDelP").modal('show');

}
function openModalAnulpro(delepro) {
    var dato = delepro.split("##");
    codiped = dato[0];
    codidped = dato[1];
    codipro = dato[2];
    nompro = dato[3];
    codmarpro = dato[4];
    codcatpro = dato[5];
    $("#textcodpedi").val(codiped);
    $("#textcoddpedi").val(codidped);
    $("#textcodipro").val(codipro);
    $("#textnompro").val(nompro);
    $("#textcodmarpro").val(codmarpro);
    $("#textcodcatepro").val(codcatpro);
    $("#myModalAnulP").modal('show');

}
function openModalCanactsrv(deleactsrv) {

    var dato = deleactsrv.split("##");
    mdcodisrv = dato[0];
    mdcodidsrv = dato[1];
    mdnomact = dato[2];
    $("#textmdcodsrv").val(mdcodisrv);
    $("#textmdcoddsrv").val(mdcodidsrv);
    $("#textmdnomact").val(mdnomact);
    $("#myModalcanserv").modal('show');

}
function openModalEjeMant(datadevo) {

    var data = datadevo.split("##");
    codipln = data[0];
    codiplndt = data[1];
    $("#textcodplnm").val(codipln);
    $("#textcodplnmdt").val(codiplndt);
    $("#myModalEjeMant").modal('show');
}
function openModalanuserdet(dato) {
    dato = dato.split("##");
    codasiserd = dato[0];
    codasiser = dato[1];
    nomtserv = dato[2];
    nomser = dato[3];
    $("#txtcodservdetmodal").val(codasiserd);
    $("#txtcodservmodal").val(codasiser);
    $("#txtnomtserModal").val(nomtserv);
    $("#txtnomserModal").val(nomser);
    $("#myModalanuserdet").modal('show');

}
function openModalvarAnu(datadevo) {
    var data = datadevo.split("##");
    codeasi = data[0];
    numasi = data[1];
    $("#txtcodasidvModal").val(codeasi);
    $("#txtcodnumasidvModal").val(numasi);
    $("#myModalVaranu").modal('show');
}

//Formulario



