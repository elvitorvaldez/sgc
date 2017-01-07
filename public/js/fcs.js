function aMays(e, elemento) {
tecla=(document.all) ? e.keyCode : e.which; 
 elemento.value = elemento.value.toUpperCase();
}


//ajax consulta Folio  
function buscaFolio()
  {
  	var ruta=  "../fcs/searchfolio";
    var FOLIO=   $("#FOLIO").val();
    
    if ($.trim(FOLIO)=='')
    {
      //document.getElementById("CUSTOMER_ID").focus();
      $("#check").addClass('hidden');    
      $("#times").addClass('hidden');  
      alertify.error("Favor de capturar un FOLIO");
      //$("#CUSTOMER_ID").focus();
    }
    else
    {
      $.ajax({
        url:ruta,  
        data: {
          folio: FOLIO }
        ,
        method: "POST"
      }
            ).done(function(msg) {
        if ($.trim(msg)=='')
        {
         
          $("#check").removeClass('hidden');
          $("#times").addClass('hidden');
           
        }
        else
        {
          $("#check").addClass('hidden');  
          $("#times").removeClass('hidden');;
          
        }
      } 
     );
    }
  }
  
/*chosen search */     
		var config = {     
		'.chosen-select': {
		no_results_text:'Sin resultados para: ',
		single_text:'Seleccione una opcion'        
		/*width:"95%"*/    
	 },  								    
	}
   for (var selector in config) {
	$(selector).chosen(config[selector]);
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
	
	 $( ".datepicker" ).datepicker();   


	   
	
/*Ajax FCS*/	
    var fcsModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#fcs");
                   $("#fcs").validate({
                   	"ignore": ":hidden:not(select)" ,  
                    "rules": {
                    	 
                        "COMPANY": { "required": true},       
                        "LOCATION_NAME": { "required": true},
                        "LOCATION": { "required": true},
                        "SERVICIO_ID": { "required": true},
                        "FOLIO": { "required": true, "minlength": 2},
                                                                          
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
                                alertify.error('Error al guardar FCS');
                            },
                            'success':function(data){
                                if (data.success === true) {    
                                    alertify.success(data.message);
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
    fcsModule.init();
}(this));   

/*Ajax FCS*/  


(function(){
    var deleteFCSModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#borraFcs");
                   $("#borraFcs").validate({
                    "rules": {
                        "idFCS": { "required": true}                       
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
    deleteFCSModule.init();
}(this));   



(function(){
    var validateFCSModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#validarFcs");
                   $("#validarFcs").validate({    
                    "rules": {
                        "idFCSval": { "required": true}                         
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
    validateFCSModule.init();
}(this));