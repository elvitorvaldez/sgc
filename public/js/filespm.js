$(document).ready(function() {       

   //$("#submit-btn").on('click',(function(e) {     
   	      $("#submit-btn").click(function(){ 
   	      	alert("hay");      
		//e.preventDefault();  
		 var form=$("#MyUploadForm");
		 var formData = new FormData();
         formData.append('FileInput', $('input[type=file]')[0].files[0]);      
		$.ajax({   
        	url: $(form).attr('action'),   
			type: "POST",
			data: formData,      
			contentType: false,
    	    cache: false,
			processData:false,      
			xhrFields: {     
		      // add listener to XMLHTTPRequest object directly for progress (jquery doesn't have this yet)
		     onprogress: function (e) {   
		      	    
              if(e.lengthComputable) {
               //calculate the percentage loaded
                    var pct = (e.loaded / e.total) * 100;

                    //log percentage loaded
                    //console.log(pct);  
                     
                    $('#progressbox').show();
				    $('#progressbar').width(pct.toPrecision(3) + '%'); //update progressbar percent complete
				    $('#statustxt').html(pct.toPrecision(3) + '%'); //update status text
				    if(pct.toPrecision(3)>50)     
				        {
				            $('#statustxt').css('color','#000'); //change status text to white after 50%
				        }                                          
                }
                		      	
		      }
		 },            
					beforeSend : function()
			{     
							
				if (window.File && window.FileReader && window.FileList && window.Blob)
				{
					
					if( !$('#FileInput').val()) //verifica que no sea vacio
					{   
					    alertify.error("No has seleccionado ningún archivo.");
						return false;
						  
					}
					
					var fsize = $('#FileInput')[0].files[0].size; //Trae el tamaño del archivo 
					var ftype = $('#FileInput')[0].files[0].type; // Trae el tipo de archivo
					
			
					//Tipos permitidos
					switch(ftype)
			        {
			        //    case 'image/png': 
						//case 'image/gif': 
						//case 'image/jpeg': 
					//	case 'image/pjpeg':     
						case 'text/plain':
						case 'text/html':
						case 'application/x-zip-compressed':
						case 'application/pdf':
						case 'application/msword':
						case 'application/vnd.ms-excel':
						//case 'video/mp4':
			                break;
			            default:
			               alertify.error("Este tipo de archivo no es soportado <b>"+ftype+"</b>");                 
			              
						return false;
			        }
					
					//Maximo tamaño permitido 5 MB (1048576)
					if(fsize>5242880) 
					{
						alertify.error("<b>"+bytesToSize(fsize) +"El archivo es demasiado grande solo puedes subir 5 MB");
				    
						return false;
					}
							
					$('#submit-btn').hide(); //esconde el boton enviar
					$('#loading-img').show(); //esconde la imagen load
					$("#output").html("");  
				}
				else
				{
					//Error de salida a los navegadores sin soporte de más antiguos que no soporta HTML5 API Archivo
					alertify.error("Por favor, actualice su navegador, debido a que su navegador actual carece de algunas nuevas características que necesitamos!");
					return false;
				}
				
			},
			success: function(data)
		    {
		    	     
		    	 if (data.success === true) {   
                     alertify.success(data.message); 
                     
                     $('#submit-btn').show(); //hide submit button
					 $('#loading-img').hide(); //hide submit button
					 $('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar  
                            
                     setTimeout(
					function() 
					{
						//location.href=data.url;    
					},1000);                                  	 
                                    
                 } else {
                 	 	
                   alertify.error(data.message);
                 }
		           
		    },
		  	error: function(e) 
	    	{     
				//$("#err").html(e).fadeIn();
				alertify.error("Algo no salió bien vuelve a intentarlo.");   
	    	} ,
	       	        
	   });
	   
	});   
	
	function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}	
	
});
