 (function(){
	//alert("hay comunicacion");    
/*Ajax Add User PM*/	
    var fcsModule=(function(){
        return {  
            'init': function(){
                this.validate();
            },  
            'validate': function(){   
                var form=$("#addUsrPM");
                   $("#addUsrPM").validate({   
                   	"ignore": ":hidden:not(select)" ,  
                    "rules": {
                    	 
                        "COMPANY": { "required": true},       
                        "userPm": { "required": true},                           
                                                                          
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
                                $("#submitUser").addClass("disabled");
                                $("#submitUser").attr("disabled","disabled");
                            },
                            'complete': function(){
                                $("#ark_loader-login").addClass("hidden");
                                $("#submitUser").removeClass("disabled");
                                $("#submitUser").removeAttr("disabled");  
                            },
                            'error': function(error) {                            	 
                                alertify.error('Error al asignar el usuario');
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

