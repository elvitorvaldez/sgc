<?php   
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\Json\Json;    

class LoginController extends AbstractActionController
{
    
    
    public function logoutAction()
    {
         $user_session = new Container('user');
        foreach ($user_session->getArrayCopy() as $key => $item) {
            unset ($user_session->$key);            
        }
        return $this->redirect()->toRoute('home');
    }
    
    
    /**
     * Iniciar sesión en Active Directory
     */
    public function loginAction()
    {

        //get user and pass 
        $username         = $this->getRequest()->getPost('userId');
        $password         = $this->getRequest()->getPost('password');
        $ldap_dn_usuarios = "CN=Users,DC=vsys,DC=com";
        $ldap_dn_roles    = "OU=SGC,DC=vsys,DC=com";
        $dn               = 'sAMAccountName=' . $username . ',OU=SGC,DC=vsys,DC=com';
        $msg="";
        $msgError=0;
		
		$unmail = explode("@",$username); 
        $usuario = $unmail[0]; 
		$domext = $unmail[1];
		
		$username =	$unmail[0];  
		
		if($domext!=""){
		
		  if($domext!="vsys.com"){	
		   $msgError=4;  
		  } 
		}    
		     
		
		//comprobar que no sean vacias 
        if ($username==""  ||  $password=="")
        {
           $msgError=1;  
        }
		
		//conexión a ldap
		$ldapconn = ldap_connect("10.1.17.54");
		 
            //Si hay conexión
        if ($ldapconn) {
        	
			// realizando la autenticación al servidor ldap
          $ldapbind = ldap_bind($ldapconn, $username . '@vsys.com', $password);
		  
		    if ($ldapbind) {                      
                $filter = "(sAMAccountName=" . $username . ")";
                $attr   = array(
                    "displayname",
                    "userPrincipalName",
                    "sAMAccountname",
                    "memberof"
                );     
                $result = ldap_search($ldapconn, $ldap_dn_usuarios, $filter, $attr);
                $entries = ldap_get_entries($ldapconn, $result);
                
                if ($entries["count"] > 0) {
                    
                    // var_dump($entries );exit;
                    
                    for ($i = 0; $i < $entries["count"]; $i++) {
                        // Store username in session
                        
                        $inicio   = stripos($entries[$i]["memberof"][0], '-') + 1;
                        $fin      = strpos($entries[$i]["memberof"][0], ',');
                        $longitud = $fin - $inicio;
                        $rol      = substr($entries[$i]["memberof"][0], $inicio, $longitud);
                        //die($rol);
                        
                        $user_session           = new Container('user');
                        $user_session->username = $entries[$i]["displayname"][0];
                        $user_session->rol     = $rol;
                        $user_session->mail     = $entries[$i]["userprincipalname"][0];
						$user_session->tiempo  = time();  
                    }
         
                  //  $this->flashMessenger()->addMessage($user_session->username . " con rol " . $user_session->userrole);
                      
                      //redireccion a dashboard
                     $jsonModel = array('url'=>'dashboard','success'=>true);     
                      
                }else {    //if count $entries            	 
					 //el usuario o la contraseña son incorrectos
                      $msgError=2;
				}
				
			}else{  //if $ldapbind
				//No se ha podido iniciar la sesión
                 $msgError=2;
			}			  
			
        		
		 }else{  //if $ldapconn conexión
               //No se pudo conectar con el servidor
                $msgError=3; 
        }	
            
             			
			switch($msgError) {
				
				case 1:
					$msg="El usuario y la contraseña no pueden ser vacíos.";					
				break;
					
				case 2:
					$msg="El usuario o contraseña son incorrectos o no existen en el grupo";	
				break;
				
				case 3:
					$msg="No se pudo conectar al servidor de autenticación.";	
				case 4:
					$msg="El correo no es de Vsys.";  		
				break;		
		  	
		  	}
			
			 
			//regresa el valor del error
			if($msg){
				
			   $jsonModel = array( 'message' => $msg,'success'=>false);            

			}
			
			$response = $this->getResponse();   
                        $response->getHeaders()->addHeaderLine( 'Content-Type', 'application/json' );
                        $response->setContent(json_encode($jsonModel));

			return $response;              
			 
          
         } // public function loginAction
           
		   
		  
        
} //end class		
        	
