<?php  
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\View\Model\JsonModel;
use Zend\Http\Request;
use Zend\Json\Json;

 
class FilepmController extends AbstractActionController {     
    
   protected $documentosTable;     
   
   public function getEmpresasTable()
    {
        if (!$this->empresasTable) {
            $sm                  = $this->serviceLocator;
            $this->empresasTable = $sm->get('Application\Model\EmpresasTable');
        }
        return $this->empresasTable;
    }
	
	public function getDocumentosTable()
    {    
        if (!$this->documentosTable) {
            $sm                  = $this->serviceLocator;
            $this->documentosTable = $sm->get('Application\Model\DocumentosTable');
        }
        return $this->documentosTable;  
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
		
		 $userMail = $user_session->mail;    	
		
          
		  $pmRelationship=$this->getUserpmTable()->getPmForCompanies($userMail);
		    
		  
		  if ($user_session->rol == "PM"){
		  	
		  	 foreach ($pmRelationship as $pmRelationship) {
		  	   $enterpriseAssigned=$this->getEmpresasTable()->getCompanyByRfc($pmRelationship->CUSTOMER_ID);  
				 
			   
			
			   if(!empty($pmRelationship)){  
				   foreach($enterpriseAssigned as $enterpriseAssigned){
				   	 $dataEnterpriseData[]=(object)[
				   	 'CUSTOMER_ID' => $enterpriseAssigned->CUSTOMER_ID,
				   	 'COMPANY' => $enterpriseAssigned->COMPANY,
				   	 ];   
				
				   }  	 	    
			   }   
			   		   
			                 
		     }   
			 
			$enterprisesList= (object)$dataEnterpriseData;               
		  }elseif($user_session->rol == "GerentePMO"){ 
		  	$enterprisesList=$this->getEmpresasTable()->getCompaniesForCombo();
		  }
		           
        if (isset($user_session->username)) {
        	  return new ViewModel(array(
			   'empresas' => $enterprisesList,   
            ));         
          } else {
         return $this->redirect()->toRoute('home');
        }
			
    			  
	}  
     
	 
	  public function addFileAction()
    {
    	
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {	
		
	         
		
		if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
        {
        	 $data  = new \Application\Model\Documentos;     
			 
        	$data->CUSTOMER_ID  = $this->params()->fromPost('COMPANY');
			$data->TIPO         = $this->params()->fromPost('DOCUMENTTYPE');   
			  
			if (!isset($data->CUSTOMER_ID)){
		     $jsonModel = array(
                'message' => "No hay una empresa seleccionada",            
                'success' => false
                );
	        }
			
			if (!isset($data->DOCUMENTTYPE)){
		     $jsonModel = array(
                'message' => "No se ha seleccionado un tipo de documento ",            
                'success' => false
                );
	        }
			
			$carpeta=strtolower($data->TIPO);
			
			$carpeta = str_replace(' ', '', $carpeta);     
			
			$directoryPath='/sgc/public/documents/pm/'.$carpeta."/";                
			       
        	$UploadDirectory	=  $_SERVER['DOCUMENT_ROOT'].$directoryPath;
			          
			if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		     $jsonModel = array(
                'message' => "Error ajax request",        
                'success' => false
                );
	        }   
			   
			if ($_FILES["FileInput"]["size"] > 5242880) {
		      //die("El archivo es muy grande...");
		      $jsonModel = array(
                'message' => "El archivo es muy grande...",
                'success' => false
               );  
		         
	        }
			 
		  switch(strtolower($_FILES['FileInput']['type']))
		   {
			//allowed file types
            case 'image/png': 
			case 'image/gif': 
			case 'image/jpeg': 
			case 'image/pjpeg':
			case 'text/plain':
			case 'text/html': //html file
			case 'application/x-zip-compressed':
			case 'application/pdf':
			case 'application/msword':
			case 'application/vnd.ms-excel':
			case 'video/mp4':
				break;
			default: //$msg="archivo no soportado";
			   $jsonModel = array(
                'message' => "Tipo de archivo no soportado",
                'success' => false
               );
				//die('Unsupported File!'); //output error
	        }

             $File_Name          = strtolower($_FILES['FileInput']['name']);
	         $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); 
	         $Random_Number      = rand(0, 9999999999); 
	         $NewFileName 		 = $Random_Number.$File_Ext;  
			
			if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
			{
				
				//guardar datos en B.D.  
				
				$data->EXTENSION   =  $File_Ext;   
				$data->NOMBRE      =  $NewFileName;
				$data->FECHA       = date('Y-m-d H:i:s');
				$data->RUTA        = $directoryPath;  
				$data->CARGADO_POR = $user_session->mail;        
				
				$getlastRow = $this->getDocumentosTable()->getLastIdDocument();        
                
                $lastRow = $getlastRow->DOCUMENTO_ID;   
                  
				$this->getDocumentosTable()->saveDocumentpm($data,$lastRow);     
				 
				
				$jsonModel = array(
                'message' => "El archivo se agrego con exito",
                'success' => true
               );
			}else{  
				$jsonModel = array(
                'message' => "No se pudo agregrar el archivo",  
                'success' => false
               );
			}
			   			 
		}else {
		       $jsonModel = array(
                'message' => "Algo salió mal, al agregar el archivo",      
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
       
}
