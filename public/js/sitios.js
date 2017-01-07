function getCustomer(ID){
	
	//var rutaBorrar=$("#rutaBorrar").val();   
	
    //alert(ID);
    $('#CODE').html(ID);  
    $('#idSitio').val(ID);       
    var link="../../../sites/borrar/"+ID;  	          
    //alert(link);
    $('#borrarSitio').attr("action", link);
}

function buscaCp(){
	
   $.ajax({
      method: "POST",
      url: "../../public/enterprise/searchcp",    
      data: {
        cp: $( "#ZIP" ).val() }
    }
          )
      .done(function( msg ) {
      var contains = msg.indexOf('Notice');
      if (contains>0)
      {
        alert ("Código postal inválido");
      }   
      var dataArray = msg.split('|');
      $.ajax({
        method: "POST",
        url: "../../public/enterprise/searchmunstate",   
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
          $("#CITY").val(dataArray[0]);
          $("#STATE").val(dataArray[1]);
        }
      }
             );
    }
  );	
	
} 

 function buscaSitio()
  {
    var LOCATION=   $("#LOCATION").val();
    var rutaSitio=   $("#rutaSitio").val();   
    $("#check").hide();
      $("#times").hide();
    if ($.trim(LOCATION)=='')
    {
      //document.getElementById("CUSTOMER_ID").focus();
      
      alertify.error("Favor de capturar un numero de sitio");
      //$("#CUSTOMER_ID").focus();
    }
    else
    {
      $.ajax({
        url:rutaSitio,
        data: {LOCATION: LOCATION } ,
        dataType : 'json',
        method: "POST"
      }
            ).success(function(msg) {
             
            if(msg.success)   
            {
                $("#check").show();
                $("#times").hide();
            }
            else
            {
               $("#check").hide();
               $("#times").show();   
            }     
      });
      
    }
  } // buscaSitio  



(function(){
	
//$("table").dataTable();  
				
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
			    

   $('#tableGridDta').DataTable( {  
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
			    	
	     		    	  
	
	$( ".datepicker" ).datepicker();
	
	
	//solo si se requiere se carga  chosen  
	var pageChosen=   $("#pageChosen").val();
	
	if(pageChosen=="1"){     
	 /*chosen search */
   var config = {
	    '.chosen-select':{
	      no_results_text:'Sin resultados para: ',
	      /*width:"95%"*/    
	    }
	    ,   
	    /* '.chosen-select-deselect'  : {allow_single_deselect:true},
	    '.chosen-select-no-single' : {disable_search_threshold:10},
	    '.chosen-select-no-results': {no_results_text:'Sin resultados'},  
	    '.chosen-select-width'     : {width:"95%"}*/  
	  };    
	  for (var selector in config) {
	    $(selector).chosen(config[selector]);
	  }
		
	}
	
    var createSitesModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#crearSitio");
                   $("#crearSitio").validate({
                    "rules": {
                        "COMPANY": { "required": true},
                        "LOCATION_NAME" : { "required": true, "minlength": 5},                        
                        "LOCATION": { "required": true, "minlength": 5, "maxlength": 80}   
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
                            	                
                            	 //alertify.error(error.statusText);              	 
                                alertify.error('Error al crear sitio');
                            },
                            'success':function(data){
                                if (data.success === true) {    
                                    //alertify.success(data.message);
                                    alertify.success("¡Se guardo con éxito! Espera un momento…");       
                                	setTimeout(
									  function()    
									  {
									    location.href=data.url;  
									  },1000);
                                    
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
    createSitesModule.init();
}(this));   


  

(function(){
    var editSitesModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#editarSitio");
                   $("#editarSitio").validate({
                    "rules": {
                    	"LOCATION_NAME" : { "required": true, "minlength": 5},                        
                        "LOCATION": { "required": true, "minlength": 5, "maxlength": 80}   
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
                                alertify.error('');  
                            },
                            'success':function(data){
                                if (data.success === true) {    
                                   //alertify.success(data.message);
                                    alertify.success("¡Edicion exitosa! Espera un momento…"); 
                                 setTimeout(
									  function()    
									  {
									    location.href=data.url;  
									  },1000);
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
    editSitesModule.init();
}(this));   




(function(){
	  
    var deleteSiteModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#borrarSitio");
                   $("#borrarSitio").validate({
                    "rules": {
                        "idSitio": { "required": true, "minlength": 10, "maxlength": 15},
                            
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
    deleteSiteModule.init();
}(this));   