


function validar_mk_flt(){
	nommk=$("#nommar").val();
	if(nommk==''){
		alert("Ingrese Marca para flota");
		$("#nommar").focus();        
		return false;            
	}  


}
function valida_conf(){
	var codlin = $("#txtipserv").val();
	var databus = $("#txtprtser").val(); 
	var codmar = $("#txtuserser").val(); 
	var codprv = $("#txtpassusr").val();
	if(codlin == '' ||  databus=='' ||  codmar==''  || codprv=='')  {
		alert("debe ingresar datos completos de la configuracion");		                  
		$("#txtipserv").focus();
		return false;
	}


}