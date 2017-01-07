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
   
   
(function(){
    var rseModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#rse");
                   $("#rse").validate({
                    "rules": {
                        "COMPANY": { "required": true},      
                        "LOCATION_NAME": { "required": true},
                        "LOCATION": { "required": true},
                        "SERVICIO_ID": { "required": true},
                        "RSENOMSOLICITO": { "required": true, "minlength": 10}, 
                        "RSENOMAUTORIZO": { "required": true, "minlength": 10} 
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
                                alertify.error('Error al guardar RSE');
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
    rseModule.init();
}(this));   




(function(){
    var deleteRSEModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){   
                var form=$("#borraRSE");
                   $("#borraRSE").validate({
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
    deleteRSEModule.init();
}(this));   