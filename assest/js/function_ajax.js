
function getTipEq(id) {
    //rucsearch=$('#rucsearch').val();

    $.ajax({
        type: 'POST',
        url: 'controllers_ajax/getTipEq',
        data: {id: id},
        dataType: 'json',
        success: function(json) {
//alert("1");
            lista = json.lista;

            str = '<select name="seletequi" required id="seletequi" class="form-control">';
            if (lista != 0) {

                cad = lista.split("&&&");
                num = cad.length;
                for (e = 0; e < num; e++) {
                    dat = cad[e].split("#$#");
                    codtipequi = dat[0];
                    nomtipequi = dat[1];
                    estrgtipequi = dat[2];
                    str += '<option value="' + codtipequi + '">' + nomtipequi + '</option>';
                }

            } else {
                str += '<option value="0">No hay resultados</option>';
            }

            str += '</select>';

            $('#seletequi').html(str);
        }
    });
}

function getbTipEq(id) {
    //rucsearch=$('#rucsearch').val();

    $.ajax({
        type: 'POST',
        url: 'controllers_ajax/getTipEq',
        data: {id: id},
        dataType: 'json',
        success: function(json) {

            lista = json.lista;

            str = '<select name="seletequi" required id="seletequi" class="form-control"> ';
           
            if (lista != 0) {

                cad = lista.split("&&&");
                num = cad.length;
                for (e = 0; e < num; e++) {
                    dat = cad[e].split("#$#");
                    codtipequi = dat[0];
                    nomtipequi = dat[1];
                    estrgtipequi = dat[2];
                    
                    str += '<option value="' + codtipequi + '">' + nomtipequi + '</option>';
                }

            } else {
                str += '<option value="0">No hay resultados</option>';
            }

            str += '</select>';

            $('#seletequi').html(str);
        }
    });
}

         

      



   