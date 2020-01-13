/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 $(function () {

 	$('#frmaddflot').validate({
 		errorElement: "span",
 		rules: {
 			txtplaca: {
 				required: true,
 				minlength: 3,
 				maxlength: 9
 			},


 			seletp: {
 				required: true,
 				minlength: 1
 			},
 			selemrk: {
 				required: true,
 				minlength: 1
 			},
 			txtmdl: {
 				required: true,
 				minlength: 2,
 				maxlength: 45
 			},
 			txtnromtr: {
 				required: true,
 				minlength: 6,
 				maxlength: 45
 			},
 			txtsermtr: {
 				required: true,
 				minlength: 6,
 				maxlength: 45
 			},
 			txtanio: {
 				required: true,
 				minlength: 4,
 				maxlength: 4
 			},
 			txtnroeje: {

 				required: true,
 				minlength: 1,
 				maxlength: 2
 			},
 			txtnrollnts: {
 				required: true,
 				minlength: 1,
 				maxlength: 3
 			},
 			txtkmts: {
 				required: true,
 				minlength: 1,
 				maxlength: 15
 			},



 		},
 		messages: {
 			txtplaca: {
 				required: "Debe ingresar la placa",
 				minlength: "debe ingresar un dato",
 				maxlength: "supera lo requerido como maximo"
 			},
 			seletp: {
 				required: "Seleccione un tipo de vehiculo",

 			},
 			selemrk: {
 				required: "Seleccione una marca el vehiculo",

 			},
 			txtmdl: {
 				required: "Debe ingresar un modelo de vehiculo",
 				minlength: "debe ingresar un dato cuerente ",
 				maxlength: "supera maximo requerido "
 			},
 			txtnromtr: {
 				required: "Ingrese Numero de motor",
 				minlength: "debe ingresar un dato cuerente ",
 				maxlength: "supera maximo requerido "
 			},
 			txtsermtr: {
 				required: "Ingrese serie de motor",
 				minlength: "debe ingresar un dato cuerente ",
 				maxlength: "supera maximo requerido "
 			},
 			txtanio: {
 				required: "Ingrese año de flota",
 				minlength: "debe ingresar un dato cuerente ",
 				maxlength: "supera maximo requerido "
 			},
 			txtnroeje: {
 				required: "Debe ingresar numero de ejes",
 			},

 			txtnrollnts: {
 				required: "Debe ingresar numero de ejes",
 			},
 			txtnroeje: {
 				required: "Debe ingresar numero de ejes",
 			},
 			txtkmts: {
 				required: "Debe ingresar kilometraje",
 			},
 		},

 	});
 });