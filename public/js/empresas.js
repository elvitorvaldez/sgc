function aMays(e, elemento) {
tecla=(document.all) ? e.keyCode : e.which; 
 elemento.value = elemento.value.toUpperCase();
}

function getCustomer(ID){
	
    //alert(ID);
    $('#RFC').html(ID);  
    $('#idEmpresa').html(ID); 
    var link='enterprise/borrar/'+ID;       
    //alert(link);
    $('#borraEmpresa').attr("action", link);     
}
 
//ajax consulta rfc  
function buscaRFC()
  {
  	var rutaEmp=   $("#rutaEmp").val();         
    var RFC=   $("#CUSTOMER_ID").val();
    if ($.trim(RFC)=='')
    {
      document.getElementById("CUSTOMER_ID").focus();
      $("#check").hide();
      $("#times").hide();
      alertify.error("Favor de capturar un RFC");
      //$("#CUSTOMER_ID").focus();
    }
    else
    {
      $.ajax({
        url:rutaEmp,
        data: {
          rfc: RFC }
        ,
        method: "POST"
      }
            ).done(function(msg) {
        if ($.trim(msg)=='')
        {
          $("#check").show();
          $("#times").hide();
           
        }
        else
        {
          $("#check").hide();
          $("#times").show();
          
        }
      } 
     );
    }
  }
 
 //ajax consulta el codigo postal 
function buscaCp()
  {
  	
  	var rutaCp= $("#rutaCp").val();
  	var rutaCom= $("#rutaCom").val();  
  	  
  	
  	 if ($( "#CODE" ).val()=="")
    {
      alert ("Ingrese un código postal");
    }
    else
    {
      $("#ADDRESS2").remove();
      $.ajax({
        method: "POST",
        url:rutaCp,    
        data: {
          cp: $( "#CODE" ).val() }
      }
            )
        .done(function( msg ) {
        var contains = msg.indexOf('Notice');
        if (contains>0)
        {
          alert ("Código postal inválido");
        }
        var dataArray = msg.split('|');
        $( ".colName" ).html(dataArray[0]);
        $("#ADDRESS2").addClass('chosen-select');
        var config = {
          '.chosen-select': {
            no_results_text:'Sin resultados para: ',
            single_text:'Seleccione una opcion'        
            /*width:"95%"*/    
          }
          ,  								    
        }
        for (var selector in config) {
          $(selector).chosen(config[selector]);
        }
        $.ajax({
          method: "POST",  
          url:rutaCom,
          data: {
            mun: dataArray[1],
            state: dataArray[2]
          }
        }
              )
          .done(function( msg ) {
          var contains = msg.indexOf('Notice');
          if (contains>0)
          {
            alert ("Código postal inválido");
          }
          else
          {
            var dataArray = msg.split('|');
            $("#ADDRESS3").val(dataArray[0]);
            $("#STATE").val(dataArray[1]);
          }
        }
               );
      }
             );
    }
  }
 
  
 //traducción datepicker     
  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '<Ant',  
    nextText: 'Sig>',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);
  //datepicker  	

(function(){
	
				/*tabla Grid*/
			   $('#tableGrid').DataTable( {
			       /* "scrollX": true,*/     
			        "language": {
			                "sProcessing":     "Procesando...",
			                "sLengthMenu":     "Mostrar _MENU_ registros",
			                "sZeroRecords":    "No se encontraron resultados",
			                "sEmptyTable":     "Ningún dato disponible en esta tabla",
			                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
			                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			                "sInfoPostFix":    "",
			                "sSearch":         "Buscar:",
			                "sUrl":            "",
			                "sInfoThousands":  ",",
			                "sLoadingRecords": "Cargando...",
			                "oPaginate": {
			                    "sFirst":    "Primero",
			                    "sLast":     "Último",
			                    "sNext":     "Siguiente",
			                    "sPrevious": "Anterior"
			                },
			                "oAria": {
			                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
			                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
			                }
			          }   
			    } );
	  
	// datepicker
	 $( ".datepicker" ).datepicker();
	
	// eventos ajax alta
    var enterpriseModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#empresa");
                   $("#empresa").validate({
                    "rules": {
                        "COMPANY": { "required": true, "minlength": 3, "maxlength": 60 },        
                        "CUSTOMER_ID": { "required": true, "minlength": 10, "maxlength": 20},
                         "COMPANYFULL": { "required": true, "minlength": 3, "maxlength": 60 }        
                        
                    },
                   
                    "errorPlacement": function ($error, $element) {
                    	
                        var name = $element.attr("name");
                        $("#error" + name).append($error);
                              
                    },
                    "submitHandler": function(form){  
                    	                
                        $.ajax({
                            type: "POST",
                            url: $(form).attr('action'),
                            data: $(form).serialize(),
                            'beforeSend': function(){   
                                $("#ark_loader-login").removeClass("hidden");
                                $("#submitLogin").addClass("disabled");
                                $("#submitLogin").attr("disabled","disabled");
                            },
                            'complete': function(){
                                $("#ark_loader-login").addClass("hidden");
                                $("#submitLogin").removeClass("disabled");
                                $("#submitLogin").removeAttr("disabled");  
                            },
                            'error': function(error) {                            	 
                                alertify.error('No se pudo enviar el formulario');  
                            },
                            'success':function(data){
                                if (data.success === true) {    
                                    alertify.success(data.message);
                                                                     
                                   alertify.success("¡Se guardo con éxito! Espera un momento…");
                                       
                                	setTimeout(
									  function()     
									  {
									    location.href=data.url;  
									  },2000);   
									    
                                } else {
                                	  
                                    alertify.error(data.message);
                                    
                                }
                            }
                        });
                    }
                });
            }
        }
    })();
    enterpriseModule.init();
}(this));   




(function(){
    var deleteEnterpriseModule=(function(){
    	
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#borraEmpresa");
                   $("#borraEmpresa").validate({
                    "rules": {
                        "idEmpresa": { "required": true, "minlength": 10, "maxlength": 15},
                       
                    },
                   
                    "errorPlacement": function ($error, $element) {
                        var name = $element.attr("name");
                        $("#error" + name).append($error);
                              
                    },
                    "submitHandler": function(form){
                       
                        $.ajax({
                        	 
                            type: "POST",
                            url: $(form).attr('action'),                                 
                            data: $(form).serialize(),
                            'beforeSend': function(){    
                                $("#ark_loader-login").removeClass("hidden");
                                $("#submitLogin").addClass("disabled");
                                $("#submitLogin").attr("disabled","disabled");
                            },
                            'complete': function(){
                                $("#ark_loader-login").addClass("hidden");
                                $("#submitLogin").removeClass("disabled");
                                $("#submitLogin").removeAttr("disabled");  
                            },
                            'error': function(error) {                            	 
                                alertify.error('Acceso denegado');
                            },
                            'success':function(data){
                                if (data.success === true) {    
                                    alertify.success(data.message);
                                 
                                    location.href=data.url;  
                                } else {
                                	  
                                    alertify.error(data.message);
                                    
                                }
                            }
                        });
                    }
                });
            }
        }
    })();
    deleteEnterpriseModule.init();
}(this));   