/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function(){
	
    var loginModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },
            'validate': function(){                
                var form=$("#loginForm");
                   $("#loginForm").validate({
                    "rules": {
                        "userId": { "required": true, "minlength": 6, "maxlength": 30},
                        "password": { "required": true, "minlength": 6, "maxlength": 60}
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
                                	alertify.success("¡Bienvenido! espera un momento...");    
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
    loginModule.init();
}(this));   
