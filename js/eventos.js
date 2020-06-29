$(document).ready(function(){


//carga ministros al cargar la pagina
cargaministros();

   

//$('select').selectpicker();

$('#datepicker').datepicker({
 	language: 'es',
 	format: 'dd-mm-yyyy',
 	todayHighlight: true,
    autoclose: true
     });

$("#datepicker2").datepicker({
    language: 'es',
  format: 'dd-mm-yyyy',
  todayHighlight: true,
    autoclose: true
     });
});


$("#edatepicker2").datepicker({
    format: "yyyy",
    //viewMode: 'years', 
    minViewMode: 2,
    maxViewMode: 2,
    autoclose: true,
});


$('.modal').on('hidden.bs.modal', function(){//limpia input  cuando cierra modal
    $(this).find('form')[0].reset();
     location.reload();
});

$('#confirm-update').on("shown.bs.modal", function () { //no usado
   //$('#esubmateria').prop('value', submateria).change();
   //alert("despues");
     //$("#auctionLabel").html('Edit auction with id '+ $(e.relatedTarget).data('id'));
     //$("#auctionTitle").html($(e.relatedTarget).data('title'));
    //$('#edit-auction-modal ').find('input#input-id').val($(e.relatedTarget).data('title'))

});

 $("#materia").on("change", function(){
  var materia = $("select#materia").val();
     if (materia != "" ) {
        $.post("buscarsubmateria.php", {bmateria: materia}, function(mensaje) {
            $("#submateria").html(mensaje);
         }); 
     } 
});

 $("#emateria").on("change", function(){
  var emateria = $("select#emateria").val();
     if (emateria != "" ) {
        $.post("buscarsubmateria.php", {bmateria: emateria}, function(mensaje) {
            $("#esubmateria").html(mensaje);
         }); 
     } 
});




/*
$('#btneditar').on('click', function(e) {
        e.preventDefault();
        alert($('#id').val());
        var dataString = $('#efolio').serialize();
        console.log('Datos serializados: '+dataString);
    }); 

*/



$('#btneditar2').on('click', function(e) {  //lecambie nmbre para que no se active mientras pruebo
            //e.preventDefault(); 
            var id = $("input#id").val();
            var erit = $("input#erit").val();
            var eanio = $("#edatepicker2").val();
            var eredactor=$("select#eredactor").val();
            var eintegrante1=$("select#eintegrante1").val();
            var eintegrante2=$("select#eintegrante2").val();
            var emateria = $("select#emateria").val();
            var esubmateria = $("select#esubmateria").val();
            var eestado = $("select#eestado").val();
            var einput = $("input#einput").val();
            
            //alertify.success(eestado);


                          $.ajax({                        
                            data: {
                                id:id,
                                erit:erit,
                                eanio:eanio,
                                eredactor:eredactor,
                                eintegrante1:eintegrante1,
                                eintegrante2:eintegrante2,
                                emateria:emateria,
                                esubmateria:esubmateria,
                                eestado:eestado,
                                einput:einput
                            },
                            url:"edsentencia.php",
                            type:"POST",
                            dataType: 'json',
                                 
                            
                            success: function(data)             
                            {
                               alertify.success(data);
                            }

                        });  


    });


$('#btnedtmin').on('click', function(e) {
            e.preventDefault(); 
           
            var id = $("#id").val();
            var edministro = $("input#edministro").val();
            
            // alert(id);
            // alert(edministro);


         $.ajax({                        
                    data: {id:id,edministro:edministro},
                    url:"edministro.php",
                    type:"POST",
                    // contentType: false,
                    // procesData: false,       
                    
                    success: function(data)             
                    {
                       //$('#confirm-update').modal('hide');
                       var msg = alertify.success(data);
                       msg.delay(1).setContent(data);
                       setTimeout(function() {$('#confirm-update').modal('hide');}, 1000);
                       
                    }

                });       


    });

  
$('#btnagregar').on('click', function(e) {
  //e.preventDefault();
  if (($('#redactor').val() == $('#integrante1').val()) || ($('#redactor').val() == $('#integrante2').val()) || ($('#integrante1').val() == $('#integrante2').val())  )

  {
    alertify.error ("Debe seleccionar diferentes ministros!");
    return false;
  }


});


$('#btneditar').on('click', function(e) {
  //e.preventDefault();
  if (($('#eredactor').val() == $('#eintegrante1').val()) || ($('#eredactor').val() == $('#eintegrante2').val()) || ($('#eintegrante1').val() == $('#eintegrante2').val())  )

  {
    alertify.error ("Debe seleccionar diferentes ministros!");
    return false;
  }


});




//});//fin document.ready








//funciones

function limpiainput(){//Limpia inputs del modal de ingreso 
	$("#ffolio :input").each(function(){
		$(this).val('');
	});
}

function selectMinistro(){
 $.post("buscarministo.php", {bmateria: materia}, function(mensaje) {
            $("#submateria").html(mensaje);
         }); 
}



function datosEditar(id,rit,anio,redactor,integrante1,integrante2,materia,submateria,estado){
    //alert(anio);
  $('#id').attr('value', id).change();
  $('#erit').attr('value', rit).change();
  $('#erit').prop('disabled', true);
  $('#edatepicker2').attr('value', anio).change();
  $('#edatepicker2').prop('disabled', true);

  
  $('#eredactor').prop('value', redactor).change();
  $('#eintegrante1').prop('value', integrante1).change();
  $('#eintegrante2').prop('value', integrante2).change();
  
  //select materia
  $('#emateria').prop('value', materia).change();
  //$('#emateria[value=materia]').attr('selected','selected');

//select submateria
  $('#esubmateria').prop('value', submateria).change();

  $('#confirm-update').on("shown.bs.modal", function () { //metod pasado participio shown para modidicar submateria despues de la puta carga del modal , me cambio a angular :(
      $('#esubmateria').prop('value', submateria).change();
    });

  $('#eestado').prop('value', estado).change();  // $('#eestado').prop('value', estado).change();

}
 

function ministroEditar(id,edministro){

  $('#id').attr('value', id).change();
  $('#edministro').attr('value', edministro).change();
           
}
 

/*
$('#tabladatos tbody').on( 'click', 'tr', function () {
        //$(this).toggleClass('selected');

  var valores= new Array();
  i=0;

  $(this).find("td").each(function(){
                valores[i]=$(this).text();
                 i++; 
                 });
            //ocupar metodo attr value  porque metodo val(algo) no cambia el dom  solo el texto cTM!
            //$('#id').val(id).trigger('change');
            $('#id').attr('value', id).trigger('change');
            
            $('#id').attr('defaultvalue', id).trigger('change');
            $('#erit').attr('value',valores[1]).trigger('change');
            $("#edatepicker2").attr('value',valores[2]).trigger('change');
            //$("#eslMinistro").attr('value',valores[2]);
*/

function cargaministros(){

$.ajax({
    url:"select2ministros.php",
        //dataType: "json",       
        success: function(response)
        {
            $('#redactor').append(response);
            $('#integrante1').append(response);
            $('#integrante2').append(response);
            $('#eredactor').append(response);
            $('#eintegrante1').append(response);
            $('#eintegrante2').append(response);
        }
    });
}



