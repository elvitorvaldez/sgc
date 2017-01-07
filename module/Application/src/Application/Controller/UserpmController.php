<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Application\Model\UserpmTable;  
use Zend\Http\Request;
use Zend\Json\Json;
use PHPMailer;

 
class UserpmController extends AbstractActionController {
    
	protected $UserpmTable;    
    /**
     * Form Authenticate
     * @return type
     */
    
    public function getEmpresasTable()  
    {
        if (!$this->empresasTable) {
            $sm                  = $this->serviceLocator;
            $this->empresasTable = $sm->get('Application\Model\EmpresasTable');
        }
        return $this->empresasTable;
    }
	 
	 public function getUserpmTable()
    {
        if (!$this->UserpmTable) {
            $sm             = $this->serviceLocator;
            $this->UserpmTable = $sm->get('Application\Model\UserpmTable');
        }
        return $this->UserpmTable;     
    }
	 
    public function indexAction() {
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');   
        
		$plugin = $this->controlAccess();
        $user_session=$plugin->getPerfil();   
		       
        if(isset($user_session->username))
         {
         
		 
		 $idEmpresa = $this->params('id');
		 
		    
		  /*consulta LDAP*/
		 $ldap_url = '10.1.17.54';		
		 $ldap_dn = "CN=Users,DC=vsys,DC=com";
			
		 $ds = ldap_connect( $ldap_url );
		 
		 $username = "sgcro@vsys.com";
		
		 $password = "2%hrAw8K56!4Fvk#ZpEfW9.7y"; 
          
		 $login = ldap_bind( $ds, $username, $password );
		 
		 
		 try{
		 			 	
				$attributes = array("displayname", "mail");
				
				$filter = "(memberof=CN=SGC-pm,OU=SGC,DC=vsys,DC=com)";
				
				$result = ldap_search($ds, $ldap_dn, $filter, $attributes);
				
				$entries = ldap_get_entries($ds, $result);
				
			    $Totalpm=$entries["count"]; 
				
				
				for ($i=0; $i < $Totalpm; $i++) {
					
				  $name=$entries[$i]['displayname'][0];
				  $mail=$entries[$i]['mail'][0];
				  
				   $allpm[]=array('name'=>$name,'mail'=>$mail);
					
				}

			}catch(Exception $e){
			 ldap_unbind($ds);
			 return;
			}
			ldap_unbind($ds);
			    
			  return new ViewModel(array(
			   'empresas' => $this->getEmpresasTable()->getCompaniesForCombo(),  
               'allPM' => $allpm,  
               'totalPM'=>$Totalpm,  
               'idEmpresa'=>$idEmpresa,         
            ));        
		  
         }
        else
        {
            return $this->redirect()->toRoute('home');
        }
    }
   
    public function adduserAction()  
    {
    	$request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $user_session = new Container('user');  
        $msg          = "";
                
        if (isset($user_session->username)) {
         $idFCS  = $this->params('id');
         $status = $this->params('controller');	 
        	
		 /* $company = $this->params()->fromPost('COMPANY');
          $userpm = $this->params()->fromPost('userPm');*/       
		 try {   
		  $data  = new \Application\Model\Userpm;
		  
		 
		  
		  $getlastRow = $this->getUserpmTable()->getLastPmuser();
                
          $lastRow = $getlastRow->PM_ID;
		  
		   $data->CUSTOMER_ID   = $this->params()->fromPost('COMPANY');
		   $data->CORREO        = $this->params()->fromPost('userPm');
		  
		   //comprueba que la signacion no exista
		   $existe=$this->getUserpmTable()->checkUser($data->CUSTOMER_ID,$data->CORREO);  
			    
			 if (isset($existe))       
	          {
	          	$jsonModel = array(
		          'message' => 'El usuario ya tiene asignada esta empresa ',
		          'success' => false
		         );
						
				$response  = $this->getResponse();
	 	        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
		        $response->setContent(json_encode($jsonModel));
		        return $response;   
	            die();
	      }
		     
		  $actualiza=$this->getUserpmTable()->checkById($data->CUSTOMER_ID);
		  
		  if (isset($actualiza)){		  	    
		   $this->getUserpmTable()->updateAddUser($data->CORREO , $actualiza->PM_ID);
		  }else{    	 		  
		   $this->getUserpmTable()->saveAddUser($data, $lastRow);		  	         	
		  }        
		  
		  $jsonModel = array(
		      'url'     => 'userpm',  
		      'message' => 'La empresa fue asignada al usuario',
		      'success' => true
		   );     
		  
		  //enviar notificación al usuario asignado
		  //Envio correo
		   $mail = new PHPMailer();     
           $mail->CharSet = 'UTF­8';
           $mail->isSMTP();
           $mail->Host     = "10.3.18.64";
           $mail->From     = "desarrollo@vsys.com";
           $mail->FromName = "Desarrollo Vsys";
           $mail->Subject  = "Sistema de Gestion CUAD";
           $mail->AddAddress($data->CORREO);             
           $mail->isHTML(true);
				
				
			 $logoApp  = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoSGCaplicativo.png";
             $logoVsys = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoVsys.png";
			 
		   ob_start(); # apertura de bufer  
                
           include $_SERVER['DOCUMENT_ROOT'].'/sgc/module/Application/view/application/mails/addUserPM.phtml';
           $mensaje = ob_get_contents();
           ob_end_clean(); # cierre de bufer
                
           $mail->Body = $mensaje;
               
           $mail->send();     	 
		  
		  
		  
		  $response  = $this->getResponse();
          $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');  
          $response->setContent(json_encode($jsonModel));
          return $response;
		 }
		 catch (Exception $e) {
                echo 'Error al guardar: ', $e->getMessage(), "\n";
           }			
		 } else {
              
            return $this->redirect()->toRoute('home');
        }	
      
	}
   
    
}
