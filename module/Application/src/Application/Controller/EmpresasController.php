<?php   
   
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Http\Request;
use Application\Model\EmpresasTable;
use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Zend\Db\Sql\Ddl\Column\Datetime;
  
use PHPMailer;

class EmpresasController extends AbstractActionController
{
    
    /**
     * Form Authenticate
     * @return type
     */
    protected $empresasTable;
    protected $sitiosTable;
    protected $cpTable;
    protected $municipiosTable;
	protected $documentosTable;
	protected $deleteEmpresasTable;
    
    public function __construct(EmpresasTable $empresasTable)
    {
        $this->empresasTable = $empresasTable;
    }
    public function getFcsTable()  
    {
        if (!$this->FcsTable) {
            $sm             = $this->serviceLocator;
            $this->FcsTable = $sm->get('Application\Model\FcsTable');
        }
        return $this->FcsTable;
    }  
    public function getUserpmTable()  
    {
        if (!$this->UserpmTable) {
            $sm             = $this->serviceLocator;
            $this->UserpmTable = $sm->get('Application\Model\UserpmTable');
        }
        return $this->UserpmTable;     
    }
	public function getDocumentosTable()
    {    
        if (!$this->documentosTable) {
            $sm                  = $this->serviceLocator;
            $this->documentosTable = $sm->get('Application\Model\DocumentosTable');
        }
        return $this->documentosTable;  
    }
	public function getDeleteEmpresasTable()
    {    
        if (!$this->deleteEmpresasTable) {
            $sm                  = $this->serviceLocator;
            $this->deleteEmpresasTable = $sm->get('Application\Model\DeleteEmpresasTable');
        }
        return $this->deleteEmpresasTable;  
    }
	
	public function normaliza ($cadena){     
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
		ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
         $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
		bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
         $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        $cadena = strtoupper($cadena);  
        return utf8_encode($cadena);
    }  
		
    public function altasAction()
    {
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        
        
        if (isset($user_session->username)) {
        		
        	$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idEmpresa = $this->params('id');
            if (!empty($idEmpresa)) {
                return new ViewModel(array(
                    'empresa' => $this->getEmpresasTable()->getEnterpriseById($idEmpresa)
                ));
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
          
    public function indexAction()
    {

        $this->layout("layout/layout_tables.phtml");
		    
		

        $user_session = new Container('user');
          
        
        if (isset($user_session->username)) { 	
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
			$empresas=$this->getEmpresasTable()->fetchAll();
			
			   			
			foreach ($empresas as $empresas){
				
				$relationship=$this->getFcsTable()->getRelationship($empresas->CUSTOMER_ID);
								
			    if(!empty($relationship)){           
			    	   //echo "no vacio<br>";    
					             
			    	foreach ($relationship as $relationship) {
			    		//echo $relationship->TIPOSERVICIO."/////<br>";         
						$relationshipService[] = $relationship->TIPOSERVICIO;     
					}  
			    }else{
			    	$relationshipService="";  
			    }  
				
				$userPm=$this->getUserpmTable()->checkById($empresas->CUSTOMER_ID);
				
				if(isset($userPm)){			
				   $mailPm = $userPm->CORREO;
				}else{
					$mailPm="";   
				}
				             
				
				$enterprise[]=array('COMPANY'=>$empresas->COMPANY,
				'CUSTOMER_ID'=>$empresas->CUSTOMER_ID,
				'TIPOSERVICIO'=>$relationshipService,
				'CORREOPM'=>$mailPm             
				);
			}
			
			      
			 //print_r($dataEnterprise);  
			    
            return new ViewModel(array(
                'empresas' => $enterprise,                      
            ));     
        } else {
            return $this->redirect()->toRoute('home');
        }


    }
    
    public function saveAction()
    {
        
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idEmpresa = $this->params('id');
            
			
			
            try {
                
                $data = new \Application\Model\Empresas;    
                
                $data->CUSTOMER_ID    = mb_strtoupper( $this->params()->fromPost('CUSTOMER_ID'),'UTF-8');  
                $customerSince=$this->params()->fromPost('CUSTOMER_SINCE');
               
			   //la empresa existe pero ya habia sido eliminada 
			   $existeDel= $this->getEmpresasTable()->getEnterpriseByIdDel($data->CUSTOMER_ID);
			   if($existeDel!=""){
			   	$idEmpresa=$existeDel->CUSTOMER_ID;  			   	
			   }   
			   
                if (trim($customerSince)!="")
                {
                      
                    $day=substr($customerSince, 0,2);
                    $month= substr($customerSince, 3,2);
                    $year=  substr($customerSince, 6,4);
                    $data->CUSTOMER_SINCE = date("Y-m-d H:i:s", mktime(0,0,0,$month,$day,$year));
					                   
                }
                else 
                {
                    $data->CUSTOMER_SINCE = date('Y-m-d H:i:s');
					                       
                }  
                  
                $data->COMPANY           =  $this->normaliza($this->params()->fromPost('COMPANY'));
				$data->COMPANY_FULL_NAME =  $this->normaliza($this->params()->fromPost('COMPANY_FULL_NAME'));
                $data->ADDRESS1          =  $this->normaliza($this->params()->fromPost('ADDRESS1'));
                $data->ADDRESS2          =  $this->normaliza($this->params()->fromPost('ADDRESS2'));
                $data->ADDRESS3          =  $this->normaliza($this->params()->fromPost('ADDRESS3'));
                $data->STATE             =  $this->normaliza($this->params()->fromPost('STATE'));
                $data->CODE              =  $this->params()->fromPost('CODE');
                $data->PHONE             =  $this->params()->fromPost('PHONE');
                $data->LAST_UPDATE       = date('Y-m-d H:i:s');     
                $data->UPDATED_BY        =  $this->normaliza($user_session->mail);   
                
                // 
				print_r($data);  
                if (trim($data->CUSTOMER_ID) == "" || trim($data->COMPANY) == "") {
                    $msg = "No se agrego un RFC o nombre de la empresa ";
					 $jsonModel = array(
                        'message' => $msg,
                        'success' => false
                      );  
                }else{
                  
                $rfc=$data->CUSTOMER_ID;     
			    $existe= $this->getEmpresasTable()->getEnterpriseById($rfc);
			
			   	 if (isset($existe) && $idEmpresa=="")    
	               {
	               	$jsonModel = array(
		                    'message' => 'Esta empresa ya se encuetra registrada',
		                    'success' => false
		                );
	            	   
			       } else{              
	                     
		                $this->getEmpresasTable()->saveAlbum($data, $idEmpresa);
		                if($idEmpresa!=""){
		                	$link="../../empresas";
		                }else{
		                	$link="../empresas";
		                }
		                $jsonModel = array(
		                    'url' => $link,     
		                    'message' => 'Empresa registrada exitosamente',
		                    'success' => true
		                );
	                }
			  } //end if si no es vacio empresa o RFC
            }
            catch (Exception $e) {
            	
				 $jsonModel = array(
                    'url' => 'index',  
                    'message' => 'Error al guardar',
                    'error'=> $e->getMessage(),
                    'success' => false
                    );
                
            }
			
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
				
        } else {
        	   
            return $this->redirect()->toRoute('home');
        }
        
    }
    
    
    
    
    public function detalleAction()
    {
        $this->layout("layout/layout_tables.phtml");     
        $user_session = new Container('user');
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idEmpresa = $this->params('id');
			
			
            $nombreEmpresa=$this->getEmpresasTable()->getCompanyByRfc($idEmpresa);
			
			$sites=$this->getSitiosTable()->listSitesByEnterpriseName($nombreEmpresa->COMPANY);
						
			foreach($sites as $sites){
			  $fcsAll= $this->getFcsTable()->fetchAllFcsBySitio($sites["LOCATION"]);
				   
			   $sitesAll[]=array(      	  
			   'COMPANY'=>$sites['COMPANY'],  
			   'LOCATION_NAME'=>$sites['LOCATION_NAME'],
			   'LOCATION'=>$sites["LOCATION"],
			   'ADDRESS'=>$sites['ADDRESS'],
			   'ZIP'=>$sites['ZIP'],
			   'STATE'=>$sites['STATE'],
			   'PHONE'=>$sites['PHONE'],
			   'FCSS'=>$fcsAll,		
			   );
			 
			}  
			  
			     
            if (!empty($idEmpresa)) {
            	    
                return new ViewModel(array(  
                    'empresa' => $this->getEmpresasTable()->getEnterpriseById($idEmpresa),
                    'documentos' => $this->getDocumentosTable()->getDocumentsPmById($idEmpresa),
                    'sitios' => $sitesAll,    
                        
                ));
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
       


    public function borrarAction()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idEmpresa = $this->params('id');
            
            try {
            	
				$data = new \Application\Model\DeleteEmpresas;    
				
            	$dataEnterprise=$this->getEmpresasTable()->getEnterpriseById($idEmpresa);
            	
				foreach ($dataEnterprise as $dataEnterprise){
					    
				   $data->CUSTOMER_ID     =  $dataEnterprise->CUSTOMER_ID;
				   $data->CUSTOMER_SINCE  =  $dataEnterprise->CUSTOMER_SINCE;	
				   $data->COMPANY         =  $dataEnterprise->COMPANY;	
				   $data->ADDRESS1        =  $dataEnterprise->ADDRESS1;	
				   $data->ADDRESS2        =  $dataEnterprise->ADDRESS2;	
				   $data->ADDRESS3        =  $dataEnterprise->ADDRESS3;	
				   $data->STATE           =  $dataEnterprise->STATE;	
				   $data->CODE            =  $dataEnterprise->CODE;	
				   $data->PHONE           =  $dataEnterprise->PHONE	;	
				   $data->LAST_UPDATE     =  $dataEnterprise->LAST_UPDATE;	
				   $data->UPDATED_BY      =  $dataEnterprise->UPDATED_BY;  
				   			
				}
				 
				
            	$this->getDeleteEmpresasTable()->saveAlbum($data);
				    
                 //enviar notificación a sm   
                 
                 //Envio de correo
                $mail          = new PHPMailer();     
                $mail->CharSet = 'UTF­8';
                $mail->isSMTP();
                $mail->Host     = "10.3.18.64";
                $mail->From     = "desarrollo@vsys.com";
                $mail->FromName = "Desarrollo Vsys";
                $mail->Subject  = "Sistema de Gestion CUAD";
                $mail->AddAddress("david_aguilar_19@hotmail.com", "David Aguilar");
                $mail->AddAddress("daguilar@vsys.com", "David Aguilar");
                $mail->AddCC("daguilar@vsys.com");
                //$mail->AddBCC("besquer@vsys.com");
                $mail->isHTML(true);
				
				
				$logoApp  = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoSGCaplicativo.png";
                $logoVsys = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoVsys.png";
				
				 ob_start(); # apertura de bufer  
                
                include $_SERVER['DOCUMENT_ROOT'].'/sgc/module/Application/view/application/mails/deleteEmpresa.phtml';
                $mensaje = ob_get_contents();
                ob_end_clean(); # cierre de bufer
                
                $mail->Body = $mensaje;
               
                $mail->send();   
                        
                $this->getEmpresasTable()->deleteAlbum($idEmpresa);
                
                $jsonModel = array(
                    'url' => 'empresas',  
                    'message' => 'Empresa eliminada exitosamente',
                    'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
            }
            catch (Exception $e) {
                echo 'Error al eliminar ', $e->getMessage(), "\n";
            }
            
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    
     public function searchcpAction()
    {
         
         $cp = $this->request->getPost("cp");
        //$cp='54933';
            if (!empty($cp)) {
               
                
               return new JsonModel(array(
                    'colonias' => $this->getCpTable()->getSuburbByCp($cp),
                   
                ));
             die();
            }
            
    }
    
         public function searchmunstateAction()
    {
         
         $mun = $this->request->getPost("mun");
         $state = $this->request->getPost("state");
        
               return new JsonModel(array(
                    'datos' => $this->getMunicipiosTable()->getMunAndState($mun,$state),
                   
                ));
          
        
    }
    
    public function searchempAction()
    {
         $retorno="";
         $rfc = $this->request->getPost("rfc");
      
         $existe=$this->getEmpresasTable()->getEnterpriseById($rfc);
         
         if (isset($existe))
         {
           die($existe->CUSTOMER_ID);
            
             $retorno = $existe->CUSTOMER_ID;  
         }
         
         
             die($retorno);
            
            
    }
    
    
    public function getEmpresasTable()
    {
        if (!$this->empresasTable) {
            $sm                  = $this->serviceLocator;
            $this->empresasTable = $sm->get('Application\Model\EmpresasTable');
        }
        return $this->empresasTable;
    }

    public function getSitiosTable()
    {
        if (!$this->sitiosTable) {
            $sm                = $this->serviceLocator;
            $this->sitiosTable = $sm->get('Application\Model\Sitios\Table');
        }
        return $this->sitiosTable;
    }
      
    
    public function getCpTable()
    {
      
        if (!$this->cpTable) {
            $sm            = $this->serviceLocator;
            $this->cpTable = $sm->get('Application\Model\CpTable');
        }
        
        return $this->cpTable;
    
    }
    
     public function getMunicipiosTable()
    {
        if (!$this->municipiosTable) {
            $sm                  = $this->serviceLocator;
            $this->municipiosTable = $sm->get('Application\Model\MunicipiosTable');
        }
        return $this->municipiosTable;
    }
 
}

